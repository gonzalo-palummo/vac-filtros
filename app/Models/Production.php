<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{

    protected $fillable = [
        'product_id', 'amount'
    ];

    public static $rules = [
        'product_id' => 'required|integer|exists:products,id',
        'amount' => 'required|numeric',
        'supplies.*.supply_id' => 'required|integer|exists:supplies,id',
        'supplies.*.wastes' => 'required|numeric|min:0'
    ];


    public static $errorMessages = [
        'product_id.required' => 'El ID del producto es requerido.',
        'product_id.integer' => 'El ID del producto debe ser de tipo integer.',
        'product_id.exists' => 'El ID del producto debe existir.',
        'amount.required' => 'La cantidad que se fabricó es requerida.',
        'amount.numeric' => 'La cantidad que se fabricó debe ser de tipo numerico.',
        'supplies.*.supply_id.required' => 'El ID del insumo es requerido.',
        'supplies.*.supply_id.integer' => 'El ID del insumo debe ser de tipo integer.',
        'supplies.*.supply_id.exists' => 'El ID del insumo debe existir.',
        'supplies.*.wastes.required' => 'El desperdicio es requerido.',
        'supplies.*.wastes.numeric' => 'El desperdicio debe ser un número.',
        'supplies.*.wastes.min' => 'El desperdicio debe ser positivo.'
    ];

    //ToDo: Replace 'products' => 'product'
    public function products()
    {
        return $this->belongsTo('App\Models\Products', 'product_id');
    }

    public function supplies()
    {
        return $this->belongsToMany('App\Models\Supplies', 'productions_supplies', 'production_id', 'supplie_id')
            ->withPivot([
                'waste',
                'created_at',
                'updated_at'
            ]);
    }
}
