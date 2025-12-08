<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'view_password',
        'type', // Original field
        'referrer_id', // New MLM field
        'user_type', // New MLM field
        'is_system_seller', // New MLM field
        'is_self_seller', // New MLM field
        'phone',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'commission_balance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'view_password', // Hide view_password from serialization
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_system_seller' => 'boolean',
        'is_self_seller' => 'boolean',
        'commission_balance' => 'decimal:2',
    ];

    /**
     * User Types Constants
     */
    const TYPE_ADMIN = 'admin';
    const TYPE_CUSTOMER = 'customer';
    const TYPE_RESELLER = 'reseller';
    const TYPE_COMPANY = 'company';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Set default user_type if not provided
            if (empty($user->user_type)) {
                $user->user_type = $user->type === self::TYPE_RESELLER ? 'reseller' : 'customer';
            }

            // Generate username if not provided
            if (empty($user->username)) {
                $user->username = strtolower(str_replace(' ', '', $user->name)) . rand(100, 999);
            }

            // Store viewable password if not set
            if (empty($user->view_password) && !empty($user->password)) {
                $user->view_password = $user->password; // In real scenario, you might want to store this differently
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the referrer who referred this user
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get all referrals of this user
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    /**
     * Get level 1 referrals (direct referrals)
     */
    public function level1Referrals()
    {
        return $this->referrals()->with('level2Referrals');
    }

    /**
     * Get level 2 referrals (referrals of referrals)
     */
    public function level2Referrals()
    {
        return $this->hasManyThrough(
            User::class,
            User::class,
            'referrer_id', // Foreign key on users table (for level 1)
            'referrer_id', // Foreign key on users table (for level 2)
            'id', // Local key on users table
            'id'  // Local key on users table (for level 1)
        );
    }

    /**
     * Get level 3 referrals
     */
    public function level3Referrals()
    {
        return $this->hasManyThrough(
            User::class,
            User::class,
            'referrer_id', // Foreign key for level 2
            'referrer_id', // Foreign key for level 3
            'id',          // Local key
            'id'           // Local key for level 2
        );
    }

    /**
     * Sales made by this user (as seller)
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'seller_id');
    }

    /**
     * Purchases made by this user (as customer)
     */
    public function purchases()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }

    /**
     * Commission transactions for this user
     */
    public function commissionTransactions()
    {
        return $this->hasMany(CommissionTransaction::class, 'seller_id');
    }

    /**
     * Commission earnings for this user
     */
    public function commissionEarnings()
    {
        return $this->hasMany(CommissionEarning::class, 'user_id');
    }

    /**
     * Wallet transactions
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope for system sellers
     */
    public function scopeSystemSellers($query)
    {
        return $query->where('is_system_seller', true)
            ->where('user_type', 'reseller');
    }

    /**
     * Scope for self sellers
     */
    public function scopeSelfSellers($query)
    {
        return $query->where('is_self_seller', true)
            ->where('user_type', 'reseller');
    }

    /**
     * Scope for resellers
     */
    public function scopeResellers($query)
    {
        return $query->where('user_type', 'reseller');
    }

    /**
     * Scope for customers
     */
    public function scopeCustomers($query)
    {
        return $query->where('user_type', 'customer');
    }

    /**
     * Scope for admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('type', self::TYPE_ADMIN);
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for users with referrals
     */
    public function scopeHasReferrals($query)
    {
        return $query->has('referrals');
    }

    // ==================== METHODS ====================

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->type === self::TYPE_ADMIN;
    }

    /**
     * Check if user is a reseller
     */
    public function isReseller()
    {
        return $this->user_type === 'reseller' || $this->type === self::TYPE_RESELLER;
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer()
    {
        return $this->user_type === 'customer' || $this->type === self::TYPE_CUSTOMER;
    }

    /**
     * Check if user is a system seller
     */
    public function isSystemSeller()
    {
        return $this->is_system_seller && $this->isReseller();
    }

    /**
     * Check if user is a self seller
     */
    public function isSelfSeller()
    {
        return $this->is_self_seller && $this->isReseller();
    }

    /**
     * Get full referral chain (up to 3 levels)
     */
    public function getReferralChain()
    {
        $chain = [
            'level1' => $this->level1Referrals()->get(),
            'level2' => $this->level2Referrals()->get(),
            'level3' => $this->level3Referrals()->get(),
        ];

        return $chain;
    }

    /**
     * Get total referral count across all levels
     */
    public function getTotalReferralsCount()
    {
        return $this->level1Referrals()->count() +
            $this->level2Referrals()->count() +
            $this->level3Referrals()->count();
    }

    /**
     * Get total commission earned
     */
    public function getTotalCommissionEarned()
    {
        return $this->commissionEarnings()->sum('amount');
    }

    /**
     * Get available commission balance
     */
    public function getCommissionBalance()
    {
        return $this->commission_balance ?? 0;
    }

    /**
     * Add commission to user's balance
     */
    public function addCommission($amount, $description = '')
    {
        $this->commission_balance += $amount;
        $this->save();

        // Record commission earning
        CommissionEarning::create([
            'user_id' => $this->id,
            'amount' => $amount,
            'description' => $description,
            'type' => 'commission'
        ]);

        return $this;
    }

    /**
     * Get sales statistics
     */
    public function getSalesStats()
    {
        $totalSales = $this->sales()->sum('amount');
        $salesCount = $this->sales()->count();
        $averageSale = $salesCount > 0 ? $totalSales / $salesCount : 0;

        return [
            'total_sales' => $totalSales,
            'sales_count' => $salesCount,
            'average_sale' => $averageSale,
            'total_commission' => $this->getTotalCommissionEarned(),
            'current_balance' => $this->getCommissionBalance(),
        ];
    }

    /**
     * Get user's performance metrics
     */
    public function getPerformanceMetrics()
    {
        $stats = $this->getSalesStats();
        $referralCount = $this->getTotalReferralsCount();

        return [
            'sales_volume' => $stats['total_sales'],
            'sales_count' => $stats['sales_count'],
            'referral_count' => $referralCount,
            'conversion_rate' => $referralCount > 0 ? ($stats['sales_count'] / $referralCount) * 100 : 0,
            'average_order_value' => $stats['average_sale'],
            'total_earnings' => $stats['total_commission'],
        ];
    }

    /**
     * Check if user can make referral (has available slots, etc.)
     */
    public function canMakeReferral()
    {
        // Add your business logic here
        // Example: Check if user has reached referral limit
        $maxReferrals = config('mlm.max_referrals_per_user', 100);

        return $this->level1Referrals()->count() < $maxReferrals;
    }

    /**
     * Create a new referral
     */
    public function createReferral(array $userData)
    {
        if (!$this->canMakeReferral()) {
            throw new \Exception('Referral limit reached');
        }

        return User::create(array_merge($userData, [
            'referrer_id' => $this->id,
            'user_type' => 'reseller', // or based on your logic
        ]));
    }
}
