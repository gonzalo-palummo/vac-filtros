<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment_methods extends Model
{
    protected $fillable = [
        'name'
    ];
    
    public function sales()
    {
        return $this->hasMany('App\Models\Sales', 'payment_method_id');
    }

    public function purchases()
    {
        return $this->hasMany('App\Models\Purchases', 'payment_method_id');
    }
}
