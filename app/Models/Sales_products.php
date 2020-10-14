<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Sales_products extends Pivot
{
    protected $fillable = [
        'sale_id', 'product_id', 'amount', 'unit_price'
    ];
}
