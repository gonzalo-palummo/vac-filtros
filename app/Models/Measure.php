<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    protected $fillable = [
        'name'
    ];
    
    public function supplies()
    {
        return $this->hasMany('App\Models\Supplies', 'measure_id');
    }
}
