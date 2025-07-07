<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'title', 'slug', 'images', 'points',
        'regular_price', 'sale_price', 'discount',
        'description', 'stock', 'status'
    ];
}
