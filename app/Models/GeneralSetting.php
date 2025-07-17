<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'logo',
        'favicon',
        'max_stock_per_user',
        'withdraw_shopping_wallet_percentage',
        'withdraw_charge',

    ];
}

