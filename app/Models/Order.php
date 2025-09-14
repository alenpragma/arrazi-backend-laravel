<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'dealer_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function dealer()
    {
        return $this->belongsTo(User::class, 'dealer_id');
    }

    public function completeOrder()
    {
        $this->status = 'completed';
        $this->save();

        $settings = GeneralSetting::first();
        $pvValue = $settings->pv_value ?? 0;
        $amount = $this->pv * $pvValue;

        Fund::whereIn('name', ['Club Fund','Insurance Fund','Poor Fund','Rank Fund'])
            ->where('status',1)
            ->increment('amount', $amount);
    }

}
