<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPriceLogs extends Model
{

    protected $fillable = [
        'price', 'product_id'
    ];
}
