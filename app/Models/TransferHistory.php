<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferHistory extends Model
{
    protected $table = 'transfer_history';

    protected $fillable = [
        'amount',
        'user_id',
        'frm',
        'to'
    ];
}
