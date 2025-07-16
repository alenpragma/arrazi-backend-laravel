<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposits';
    protected $fillable = [
        'user_id',
        'payment_method',
        'transaction_id',
        'amount',
        'currency',
        'type',
        'status',
        'number',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }
}
