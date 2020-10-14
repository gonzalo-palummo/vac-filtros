<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Purchases_supplies extends Pivot
{
    protected $fillable = [
        'purchase_id', 'supplie_id', 'amount', 'unit_price'
    ];
}
