<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'totalNewMember'
    ];
}
