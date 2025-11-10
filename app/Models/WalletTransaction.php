<?php
// app/Models/WalletTransaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'amount', 'type', 'description', 'balance_after', 'reference_type', 'reference_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
