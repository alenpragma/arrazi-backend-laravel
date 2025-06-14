<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'product_id',
        'quantity',
        'amount',
        'status',
        'payment_method',
        'pv',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
