<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Curfoxapiservice
{
    private const BASE_URL     = 'https://v1.api.curfox.com/api/public/merchant';
    private const TENANT       = 'royalexpress';
    private const EMAIL        = 'disanayakas22711@gmail.com';
    private const PASSWORD     = '1234';
    private const TOKEN_CACHE  = 'curfox_bearer_token';
    private const CITIES_CACHE = 'curfox_states_cities';

    // ──────────────────────────────────────────────────────────────────────
    //  AUTHENTICATION
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Return a valid bearer token, pulling from cache when possible.
     */
    public function getToken(): string
    {
        return Cache::remember(self::TOKEN_CACHE, now()->addHours(23), function () {
            return $this->freshLogin();
        });
    }

    /**
     * Force a new login and refresh the cached token.
     */
    public function refreshToken(): string
    {
        Cache::forget(self::TOKEN_CACHE);
        $token = $this->freshLogin();
        Cache::put(self::TOKEN_CACHE, $token, now()->addHours(23));
        return $token;
    }

    private function freshLogin(): string
    {
        $response = Http::withHeaders($this->baseHeaders(false))
            ->post(self::BASE_URL . '/login', [
                'email'    => self::EMAIL,
                'password' => self::PASSWORD,
            ]);

        if (! $response->successful()) {
            Log::error('Curfox login failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new \RuntimeException('Curfox API login failed: ' . $response->body());
        }

        $token = $response->json('data.token') ?? $response->json('token');

        if (! $token) {
            throw new \RuntimeException('Curfox API: no token in login response');
        }

        return $token;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  STATES & CITIES
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Return all states with their cities.
     * Cached for 24 h to avoid hammering the API on every page load.
     *
     * @return array{id: int, name: string, cities: array}[]
     */
    public function getStatesWithCities(): array
    {
        return Cache::remember(self::CITIES_CACHE, now()->addHours(24), function () {
            return $this->fetchStatesWithCities();
        });
    }

    /**
     * Refresh the states/cities cache and return fresh data.
     */
    public function refreshStatesWithCities(): array
    {
        Cache::forget(self::CITIES_CACHE);
        return $this->getStatesWithCities();
    }

    private function fetchStatesWithCities(): array
    {
        $response = $this->authenticatedGet('/state');

        if (! $response->successful()) {
            Log::error('Curfox fetch states failed', ['status' => $response->status()]);
            throw new \RuntimeException('Failed to fetch states from Curfox API');
        }

        return $response->json('data') ?? [];
    }

    /**
     * Return a flat list of active cities across all states.
     *
     * @return array{id: int, name: string, state_id: int, state_name: string}[]
     */
    public function getAllCities(): array
    {
        $states = $this->getStatesWithCities();
        $cities = [];

        foreach ($states as $state) {
            foreach ($state['cities'] ?? [] as $city) {
                if ($city['is_active'] ?? true) {
                    $cities[] = [
                        'id'         => $city['id'],
                        'name'       => $city['name'],
                        'state_id'   => $state['id'],
                        'state_name' => $state['name'],
                    ];
                }
            }
        }

        return $cities;
    }

    /**
     * Find a city by name (case-insensitive) and return its state info.
     */
    public function findCityByName(string $cityName): ?array
    {
        $lower = strtolower(trim($cityName));

        foreach ($this->getStatesWithCities() as $state) {
            foreach ($state['cities'] ?? [] as $city) {
                if (strtolower($city['name']) === $lower) {
                    return [
                        'city_id'    => $city['id'],
                        'city_name'  => $city['name'],
                        'state_id'   => $state['id'],
                        'state_name' => $state['name'],
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Find a city by its ID.
     */
    public function findCityById(int $cityId): ?array
    {
        foreach ($this->getStatesWithCities() as $state) {
            foreach ($state['cities'] ?? [] as $city) {
                if ((int) $city['id'] === $cityId) {
                    return [
                        'city_id'    => $city['id'],
                        'city_name'  => $city['name'],
                        'state_id'   => $state['id'],
                        'state_name' => $state['name'],
                    ];
                }
            }
        }

        return null;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  ORDER CREATION
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Create one or more orders via the Curfox bulk endpoint.
     *
     * @param  array  $generalData   Keys: merchant_business_id, origin_city_id|origin_city_name, origin_state_name
     * @param  array  $ordersData    Array of order objects
     * @return array  API response
     * @throws \RuntimeException on HTTP errors
     */
    public function createOrders(array $generalData, array $ordersData): array
    {
        $payload = [
            'general_data' => $generalData,
            'order_data'   => $ordersData,
        ];

        $response = $this->authenticatedPost('/order/bulk', $payload);

        if ($response->status() === 401) {
            // Token expired – refresh and retry once
            $this->refreshToken();
            $response = $this->authenticatedPost('/order/bulk', $payload);
        }

        if (! $response->successful()) {
            Log::error('Curfox create order failed', [
                'payload'  => $payload,
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);

            throw new \RuntimeException(
                $response->json('message') ?? 'Curfox order creation failed',
                $response->status()
            );
        }

        return $response->json();
    }

    /**
     * Build a single order_data item from an EmployeeDeliveryCall model.
     */
    public function buildOrderDataFromDeliveryCall(
        \App\Models\EmployeeDeliveryCall $call,
        ?int  $destinationCityId   = null,
        ?string $destinationCityName  = null,
        ?string $destinationStateName = null
    ): array {
        // Resolve destination from the call's district/city if not explicitly passed
        if (! $destinationCityId && ! $destinationCityName) {
            $cityInfo = $this->findCityByName($call->city ?? $call->district ?? '');
            if ($cityInfo) {
                $destinationCityId    = $cityInfo['city_id'];
                $destinationCityName  = $cityInfo['city_name'];
                $destinationStateName = $cityInfo['state_name'];
            }
        }

        $order = [
            'order_no'          => $call->order_code,
            'customer_name'     => $call->customer_name,
            'customer_address'  => $call->customer_address ?? '',
            'customer_phone'    => $call->customer_phone_01,
            'cod'               => (int) round($call->price * $call->qty),
            'weight'            => 1,
            'description'       => $call->item_name,
            'remark'            => $call->remark ?? '',
        ];

        if ($call->customer_phone_02) {
            $order['customer_secondary_phone'] = $call->customer_phone_02;
        }

        if ($destinationCityId) {
            $order['destination_city_id'] = $destinationCityId;
        } elseif ($destinationCityName) {
            $order['destination_city_name']  = $destinationCityName;
            $order['destination_state_name'] = $destinationStateName;
        }

        return $order;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  HELPERS
    // ──────────────────────────────────────────────────────────────────────

    private function authenticatedGet(string $endpoint): \Illuminate\Http\Client\Response
    {
        return Http::withHeaders($this->baseHeaders(true))
            ->get(self::BASE_URL . $endpoint);
    }

    private function authenticatedPost(string $endpoint, array $data): \Illuminate\Http\Client\Response
    {
        return Http::withHeaders($this->baseHeaders(true))
            ->post(self::BASE_URL . $endpoint, $data);
    }

    private function baseHeaders(bool $withAuth): array
    {
        $headers = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'X-tenant'     => self::TENANT,
        ];

        if ($withAuth) {
            $headers['Authorization'] = 'Bearer ' . $this->getToken();
        }

        return $headers;
    }
}
