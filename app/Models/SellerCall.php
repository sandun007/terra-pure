<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SellerCall extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'seller_calls';

    protected $fillable = [
        'seller_id',
        'product_id',
        'order_code',
        'call_number',
        'date',
        'customer_name',
        'customer_phone_01',
        'customer_phone_02',
        'customer_address',
        'city',
        'district',
        // ── FIX #1: these were missing from fillable → silent data drop on create/update ──
        'destination_city_id',
        'destination_state_name',
        // ─────────────────────────────────────────────────────────────────────────────────
        'item_code',
        'item_name',
        'qty',
        'price',
        'total',
        'status',
        'status_date',
        'remark',
        'platform',
        'ad_name',
        'campaign_name',
        'lead_status',
        'download_status',
        'call_duration',
        'call_recording_url',
    ];

    protected $casts = [
        'date'                => 'date',
        'status_date'         => 'datetime',
        'qty'                 => 'integer',
        'price'               => 'decimal:2',
        'total'               => 'decimal:2',
        'download_status'     => 'boolean',
        // ── FIX #1 cont.: cast new columns ──
        'destination_city_id' => 'integer',
        // ─────────────────────────────────────
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
        'deleted_at'          => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(SellerProduct::class, 'product_id');
    }

    public function employeeDeliveryCall()
    {
        return $this->hasOne(EmployeeDeliveryCall::class, 'call_id');
    }

    // ── Generators ────────────────────────────────────────────────────────

    public static function generateCallNumber(): string
    {
        $prefix = 'CALL/';

        // ── FIX #9: add lockForUpdate to prevent race condition ──
        return DB::transaction(function () use ($prefix) {
            $last = DB::table('seller_calls')
                ->select('call_number')
                ->whereNotNull('call_number')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $next = 1;
            if ($last && $last->call_number) {
                $num  = (int) str_replace($prefix, '', $last->call_number);
                $next = $num + 1;
            }

            return $prefix . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
        });
        // ─────────────────────────────────────────────────────────
    }

    public static function generateOrderCode(): string
    {
        $prefix = 'DX/ORD/';

        return DB::transaction(function () use ($prefix) {
            $last = DB::table('seller_calls')
                ->select('order_code')
                ->whereNotNull('order_code')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $next = 1;

            if ($last && ! empty($last->order_code)) {
                $digits = preg_replace('/\D+/', '', $last->order_code);
                $next   = ((int) $digits) + 1;
            }

            return $prefix . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
        });
    }

    // ── Sync ──────────────────────────────────────────────────────────────

    public function syncToEmployeeDelivery(): EmployeeDeliveryCall
    {
        if ($this->status !== 'Conform') {
            throw new \Exception('Only Conform calls can sync to employee delivery.');
        }

        if (empty($this->order_code)) {
            for ($i = 0; $i < 3; $i++) {
                try {
                    $this->order_code = self::generateOrderCode();
                    $this->saveQuietly();
                    break;
                } catch (Throwable $e) {
                    if ($i === 2) throw $e;
                }
            }
        }

        $existing = EmployeeDeliveryCall::where('call_id', $this->id)->first();

        $payload = [
            'seller_id'          => $this->seller_id,
            'product_id'         => $this->product_id,
            'call_id'            => $this->id,
            'order_code'         => $this->order_code,
            'call_number'        => $this->call_number,
            'date'               => $this->date,
            'status_date'        => $this->status_date,

            'customer_name'      => $this->customer_name,
            'customer_phone_01'  => $this->customer_phone_01,
            'customer_phone_02'  => $this->customer_phone_02,
            'customer_address'   => $this->customer_address,
            'city'               => $this->city,
            'district'           => $this->district,

            // ── FIX #2: copy Curfox city fields so dispatch works later ──
            'destination_city_id'    => $this->destination_city_id,
            'destination_state_name' => $this->destination_state_name,
            // ──────────────────────────────────────────────────────────────

            'item_code'          => $this->item_code,
            'item_name'          => $this->item_name,
            'qty'                => $this->qty,
            'price'              => $this->price,
            'total'              => $this->total,

            'remark'             => $this->remark,
            'platform'           => $this->platform,
            'ad_name'            => $this->ad_name,
            'campaign_name'      => $this->campaign_name,
            'lead_status'        => $this->lead_status,
            'call_duration'      => $this->call_duration,
            'call_recording_url' => $this->call_recording_url,
            'download_status'    => $this->download_status,
        ];

        if ($existing) {
            // DO NOT overwrite employee's own status
            $existing->update($payload);
            return $existing;
        }

        $payload['status'] = 'Pending';
        return EmployeeDeliveryCall::create($payload);
    }

    // ── Events ────────────────────────────────────────────────────────────

    private function createPendingCallFromOrder($order, $user, $product)
    {
        try {
            // Generate call number
            $callNumber = SellerCall::generateCallNumber();

            // Generate order code for the call
            $orderCode = SellerCall::generateOrderCode();

            // Create the pending call - matching your database schema
            $call = SellerCall::create([
                'seller_id'              => $user->id,
                'product_id'             => $product->id,
                'call_number'            => $callNumber,
                'order_code'             => $orderCode,  // Use generated order code
                'date'                   => now()->toDateString(),
                'customer_name'          => $order->customer_name,
                'customer_phone_01'      => $order->customer_phone,
                'customer_phone_02'      => null,
                'customer_address'       => $order->customer_address,
                'city'                   => $order->destination_city_name,
                'district'               => $order->district,

                // These fields exist in your table (curfox_* columns)
                'curfox_city_id'         => $order->destination_city_id,
                'curfox_city_name'       => $order->destination_city_name,
                'curfox_state_name'      => $order->destination_state_name,

                // Item details
                'item_code'              => $product->code,
                'item_name'              => $product->name,
                'qty'                    => $order->quantity,
                'price'                  => $product->display_price,
                'total'                  => $order->total_amount,

                // Status
                'status'                 => 'Pending',
                'status_date'            => now(),

                // Additional info
                'remark'                 => $order->remark ?? 'Auto-generated from order',
                'platform'               => $order->platform ?? 'manual',
                'download_status'        => false,
            ]);

            Log::info('Pending call auto-created from order', [
                'order_id' => $order->id,
                'call_id' => $call->id,
                'call_number' => $call->call_number,
                'order_number' => $order->order_number
            ]);

            return $call;

        } catch (Exception $e) {
            Log::error('Failed to create pending call from order', [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't throw - we don't want to fail the order if call creation fails
            return null;
        }
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function (SellerCall $call) {
            if (empty($call->call_number)) {
                $call->call_number = self::generateCallNumber();
            }

            if ($call->status && empty($call->status_date)) {
                $call->status_date = now();
            }

            if (empty($call->total) && $call->qty && $call->price) {
                $call->total = $call->qty * $call->price;
            }

            if ($call->status === 'Conform' && empty($call->order_code)) {
                $call->order_code = self::generateOrderCode();
            }
        });

        static::updating(function (SellerCall $call) {
            if ($call->isDirty(['qty', 'price'])) {
                $call->total = (int) $call->qty * (float) $call->price;
            }

            if ($call->isDirty('status') && $call->status === 'Conform' && empty($call->order_code)) {
                $call->order_code = self::generateOrderCode();
            }
        });

        static::created(function (SellerCall $call) {
            if ($call->status === 'Conform') {
                try {
                    $call->syncToEmployeeDelivery();
                } catch (Throwable $e) {
                    Log::error('SellerCall created sync failed', [
                        'call_id' => $call->id,
                        'error'   => $e->getMessage(),
                    ]);
                }
            }
        });

        static::updated(function (SellerCall $call) {
            // ── FIX #3 & #12: only sync when status actually changed TO Conform.
            //    The old code had overlapping conditions:
            //      ($becameConform || ($isConformNow && $call->wasChanged()))
            //    The second branch re-synced on EVERY field change while Conform
            //    (e.g. editing remark would trigger a redundant sync).
            //    Now we only sync on the specific status transition. ──
            if ($call->wasChanged('status') && $call->status === 'Conform') {
                try {
                    $call->syncToEmployeeDelivery();
                } catch (Throwable $e) {
                    Log::error('SellerCall updated sync failed', [
                        'call_id' => $call->id,
                        'error'   => $e->getMessage(),
                    ]);
                }
            }
        });

        static::deleted(function (SellerCall $call) {
            EmployeeDeliveryCall::where('call_id', $call->id)->delete();
        });
    }
}
