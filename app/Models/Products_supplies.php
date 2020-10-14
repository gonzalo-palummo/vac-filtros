<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products_supplies extends Model
{
    protected $fillable = [
        'product_id', 'supplie_id', 'supplie_amount'
    ];
}
