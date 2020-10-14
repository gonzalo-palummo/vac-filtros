<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales_status extends Model
{
    protected $fillable = [
        'name'
    ];

    public function sales()
    {
        return $this->hasMany('App\Models\Sales', 'sale_status_id');
    }
}
