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
        'date' => 'date',
        'status_date' => 'datetime',
        'qty' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'download_status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ─────────────────────────────────────────────────────────
    // RELATIONSHIPS
    // ─────────────────────────────────────────────────────────
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
        // if you want include soft-deleted delivery rows, add ->withTrashed()
        return $this->hasOne(EmployeeDeliveryCall::class, 'call_id');
    }

    // ─────────────────────────────────────────────────────────
    // GENERATORS
    // ─────────────────────────────────────────────────────────
    public static function generateCallNumber(): string
    {
        $prefix = 'CALL/';
        $last = self::latest('id')->first();

        $next = 1;
        if ($last && $last->call_number) {
            $num = (int) str_replace($prefix, '', $last->call_number);
            $next = $num + 1;
        }

        return $prefix . str_pad((string)$next, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate order code: DX/ORD/00004 (5 digits)
     * Uses transaction + lockForUpdate to reduce duplicates.
     */
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

            if ($last && !empty($last->order_code)) {
                // DX/ORD/00004 -> 4
                $digits = preg_replace('/\D+/', '', $last->order_code);
                $next = ((int)$digits) + 1;
            }

            return $prefix . str_pad((string)$next, 5, '0', STR_PAD_LEFT);
        });
    }

    // ─────────────────────────────────────────────────────────
    // SYNC TO EMPLOYEE DELIVERY
    // ─────────────────────────────────────────────────────────
    public function syncToEmployeeDelivery(): EmployeeDeliveryCall
    {
        // Only sync when Conform
        if ($this->status !== 'Conform') {
            throw new \Exception('Only Conform calls can sync to employee delivery.');
        }

        // Ensure seller order_code exists
        if (empty($this->order_code)) {
            // try a few times if unique constraint hits
            for ($i = 0; $i < 3; $i++) {
                try {
                    $this->order_code = self::generateOrderCode();
                    $this->saveQuietly(); // avoid looping events
                    break;
                } catch (Throwable $e) {
                    if ($i === 2) throw $e;
                }
            }
        }

        $existing = EmployeeDeliveryCall::where('call_id', $this->id)->first();

        // Data that always should match seller_calls
        $payload = [
            'seller_id'         => $this->seller_id,
            'product_id'        => $this->product_id,
            'call_id'           => $this->id,
            'order_code'        => $this->order_code,
            'call_number'       => $this->call_number,
            'date'              => $this->date,
            'status_date'       => $this->status_date,
            'customer_name'     => $this->customer_name,
            'customer_phone_01' => $this->customer_phone_01,
            'customer_phone_02' => $this->customer_phone_02,
            'customer_address'  => $this->customer_address,
            'city'              => $this->city,
            'district'          => $this->district,
            'item_code'         => $this->item_code,
            'item_name'         => $this->item_name,
            'qty'               => $this->qty,
            'price'             => $this->price,
            'total'             => $this->total,
            'remark'            => $this->remark,
            'platform'          => $this->platform,
            'ad_name'           => $this->ad_name,
            'campaign_name'     => $this->campaign_name,
            'lead_status'       => $this->lead_status,
            'call_duration'     => $this->call_duration,
            'call_recording_url'=> $this->call_recording_url,
            'download_status'   => $this->download_status,
        ];

        if ($existing) {
            // IMPORTANT: never overwrite employee status once created
            // (delivery team owns that status)
            $existing->update($payload);
            return $existing;
        }

        // New delivery record must start Pending
        $payload['status'] = 'Pending';

        return EmployeeDeliveryCall::create($payload);
    }

    // ─────────────────────────────────────────────────────────
    // MODEL EVENTS
    // ─────────────────────────────────────────────────────────
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

            // If created as Conform, generate order_code
            if ($call->status === 'Conform' && empty($call->order_code)) {
                $call->order_code = self::generateOrderCode();
            }
        });

        static::updating(function (SellerCall $call) {
            if ($call->isDirty(['qty', 'price'])) {
                $call->total = (int)$call->qty * (float)$call->price;
            }

            // If status is becoming Conform, generate order_code
            if ($call->isDirty('status') && $call->status === 'Conform' && empty($call->order_code)) {
                $call->order_code = self::generateOrderCode();
            }
        });

        static::created(function (SellerCall $call) {
            if ($call->status === 'Conform') {
                try {
                    $call->syncToEmployeeDelivery();
                } catch (Throwable $e) {
                    Log::error('SellerCall created sync failed', ['call_id' => $call->id, 'error' => $e->getMessage()]);
                }
            }
        });

        static::updated(function (SellerCall $call) {
            // If status changed to Conform OR already Conform and something changed,
            // sync details to employee table (but do NOT overwrite employee status).
            $becameConform = $call->wasChanged('status') && $call->status === 'Conform';
            $isConformNow  = $call->status === 'Conform';

            if ($becameConform || ($isConformNow && $call->wasChanged())) {
                try {
                    $call->syncToEmployeeDelivery();
                } catch (Throwable $e) {
                    Log::error('SellerCall updated sync failed', ['call_id' => $call->id, 'error' => $e->getMessage()]);
                }
            }
        });

        static::deleted(function (SellerCall $call) {
            // Optional: delete employee row when seller call deleted
            EmployeeDeliveryCall::where('call_id', $call->id)->delete();
        });
    }
}
