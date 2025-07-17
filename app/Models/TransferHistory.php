<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferHistory extends Model
{
    protected $table = 'transfer_history';

    protected $fillable = [
        'amount',
        'user_id',
        'from',
        'to',
        'type'
    ];
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
