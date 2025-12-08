<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_session_id',
        'product_id',
        'wholesaler_id',
        'quantity',
        'price',
        'total_price',
        'options',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'options' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(WholesalerProduct::class);
    }

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    // Methods
    public function updateTotal()
    {
        $this->total_price = $this->price * $this->quantity;
        $this->save();
    }

    public function increaseQuantity($amount = 1)
    {
        $this->quantity += $amount;
        $this->updateTotal();
    }

    public function decreaseQuantity($amount = 1)
    {
        $this->quantity = max(1, $this->quantity - $amount);
        $this->updateTotal();
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForGuest($query, $sessionId)
    {
        return $query->where('guest_session_id', $sessionId);
    }

    public function scopeWithProduct($query)
    {
        return $query->with('product');
    }
}
