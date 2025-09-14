<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DealerBonusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'order_id',
        'amount',
        'description',
    ];

    public function dealer()
    {
        return $this->belongsTo(User::class, 'dealer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
