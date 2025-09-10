<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundBonusHistory extends Model
{
    protected $table = 'fund_bonus_history';

    protected $fillable = [
        'user_id',
        'fund_name',
        'amount',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
