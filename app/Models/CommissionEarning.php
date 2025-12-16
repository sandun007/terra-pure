<?php
// app/Models/CommissionEarning.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'amount', 'description', 'type', 'sale_id', 'level'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
