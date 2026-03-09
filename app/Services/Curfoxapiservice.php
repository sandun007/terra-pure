<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Curfoxapiservice
{
    private const BASE_URL      = 'https://v1.api.curfox.com/api/public/merchant';
    private const TENANT        = 'royalexpress';
    private const EMAIL         = 'disanayakas22711@gmail.com';
    private const PASSWORD      = '1234';
    private const TOKEN_CACHE   = 'curfox_bearer_token';
    private const CITIES_CACHE  = 'curfox_states_cities';
    private const BIZ_CACHE     = 'curfox_businesses';

    // ── Authentication ────────────────────────────────────────────────────

    public function getToken(): string
    {
        return Cache::remember(self::TOKEN_CACHE, now()->addHours(23), function () {
            return $this->freshLogin();
        });
    }

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

    // ── States & Cities ───────────────────────────────────────────────────

    public function getStatesWithCities(): array
    {
        return Cache::remember(self::CITIES_CACHE, now()->addHours(24), function () {
            return $this->fetchStatesWithCities();
        });
    }

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

    // ── Businesses ────────────────────────────────────────────────────────

    public function getBusinesses(): array
    {
        return Cache::remember(self::BIZ_CACHE, now()->addHour(), function () {
            return $this->fetchBusinesses();
        });
    }

    public function refreshBusinesses(): array
    {
        Cache::forget(self::BIZ_CACHE);
        return $this->getBusinesses();
    }

    private function fetchBusinesses(): array
    {
        $response = $this->authenticatedGet('/business');

        if (! $response->successful()) {
            Log::error('Curfox fetch businesses failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new \RuntimeException('Failed to fetch businesses from Curfox API');
        }

        return $response->json('data') ?? [];
    }

    public function getDefaultBusiness(): ?array
    {
        $businesses = $this->getBusinesses();

        foreach ($businesses as $business) {
            if ($business['is_default'] ?? false) {
                return $business;
            }
        }

        return $businesses[0] ?? null;
    }

    /**
     * Create a new business via Curfox API.
     *
     * POST /api/public/merchant/business
     *
     * Payload shape:
     * {
     *   "business_name": "...",
     *   "business_bank_detail": { account_name, account_number, bank_name, branch_name },
     *   "business_address": { address_line_1, address_line_2, city_id, phone, postal_code, type, is_default }
     * }
     *
     * Returns the full response array (data key contains created business).
     */
    public function createBusiness(array $payload): array
    {
        $response = $this->authenticatedPost('/business', $payload);

        if ($response->status() === 401) {
            $this->refreshToken();
            $response = $this->authenticatedPost('/business', $payload);
        }

        if (! $response->successful()) {
            Log::error('Curfox create business failed', [
                'payload'  => $payload,
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);

            $body    = $response->json();
            $message = $body['message'] ?? 'Curfox business creation failed';

            if (! empty($body['errors'])) {
                $flat = [];
                array_walk_recursive($body['errors'], function ($v) use (&$flat) { $flat[] = $v; });
                $message .= ': ' . implode('; ', $flat);
            }

            throw new \RuntimeException($message, $response->status());
        }

        // Invalidate business cache so getBusinesses() picks up the new one
        Cache::forget(self::BIZ_CACHE);

        return $response->json();
    }

    // ── Order Creation ────────────────────────────────────────────────────

    public function createOrders(array $generalData, array $ordersData): array
    {
        $payload = [
            'general_data' => $generalData,
            'order_data'   => $ordersData,
        ];

        $response = $this->authenticatedPost('/order/bulk', $payload);

        if ($response->status() === 401) {
            $this->refreshToken();
            $response = $this->authenticatedPost('/order/bulk', $payload);
        }

        if (! $response->successful()) {
            Log::error('Curfox create order failed', [
                'payload'  => $payload,
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);

            $body    = $response->json();
            $message = $body['message'] ?? 'Curfox order creation failed';

            if (! empty($body['errors'])) {
                $flat = [];
                array_walk_recursive($body['errors'], function ($v) use (&$flat) { $flat[] = $v; });
                $message .= ': ' . implode('; ', $flat);
            }

            throw new \RuntimeException($message, $response->status());
        }

        return $response->json();
    }

    public function createOrder(array $payload): array
    {
        $response = $this->authenticatedPost('/order/single', $payload);

        if ($response->status() === 401) {
            $this->refreshToken();
            $response = $this->authenticatedPost('/order/single', $payload);
        }

        if (! $response->successful()) {
            $body    = $response->json();
            $message = $body['message'] ?? 'Curfox order creation failed';

            if (! empty($body['errors'])) {
                $flat = [];
                array_walk_recursive($body['errors'], function ($v) use (&$flat) { $flat[] = $v; });
                $message .= ': ' . implode('; ', $flat);
            }

            throw new \RuntimeException($message, $response->status());
        }

        return $response->json();
    }

    public function buildOrderDataFromDeliveryCall(
        \App\Models\EmployeeDeliveryCall $call,
        ?int    $destinationCityId    = null,
        ?string $destinationCityName  = null,
        ?string $destinationStateName = null
    ): array {
        if (! $destinationCityId && ! $destinationCityName) {
            $cityInfo = $this->findCityByName($call->city ?? $call->district ?? '');
            if ($cityInfo) {
                $destinationCityId    = $cityInfo['city_id'];
                $destinationCityName  = $cityInfo['city_name'];
                $destinationStateName = $cityInfo['state_name'];
            }
        }

        $order = [
            'order_no'         => $call->order_code,
            'customer_name'    => $call->customer_name,
            'customer_address' => $call->customer_address ?? '',
            'customer_phone'   => $call->customer_phone_01,
            'cod'              => (int) round(($call->price ?? 0) * ($call->qty ?? 1)),
            'weight'           => 1,
            'description'      => $call->item_name,
            'remark'           => $call->remark ?? '',
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

    // ── Tracking ──────────────────────────────────────────────────────────

    public function getTrackingInfo(string $waybillNumber): array
    {
        $payload = json_encode(['waybill_number' => $waybillNumber]);

        $response = Http::withHeaders($this->baseHeaders(true))
            ->withBody($payload, 'application/json')
            ->get(self::BASE_URL . '/order/tracking-info');

        if ($response->status() === 401) {
            $this->refreshToken();
            $response = Http::withHeaders($this->baseHeaders(true))
                ->withBody($payload, 'application/json')
                ->get(self::BASE_URL . '/order/tracking-info');
        }

        if (! $response->successful()) {
            $body    = $response->json();
            $message = $body['message'] ?? "Tracking fetch failed (HTTP {$response->status()})";
            throw new \RuntimeException($message, $response->status());
        }

        return $response->json();
    }

    // ── HTTP helpers ──────────────────────────────────────────────────────

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
