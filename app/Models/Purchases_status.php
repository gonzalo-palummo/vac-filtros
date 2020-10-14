<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchases_status extends Model
{
    protected $fillable = [
        'name'
    ];

    public function purchases()
    {
        return $this->hasMany('App\Models\Purchases', 'purchase_status_id');
    }
}
