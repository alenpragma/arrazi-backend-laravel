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
}
