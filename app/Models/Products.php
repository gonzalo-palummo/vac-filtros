<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'category_id', 'name', 'code', 'measurement', 'mts', 'stock', 'price1', 'price2', 'price3'
    ];

    public static $rules = [        
        'category_id' => 'required|integer|exists:categories,id',
        'name' => 'required|string',
        'code' => 'required|string',
        'measurement' => 'required|string',
        'mts' => 'required|numeric',
        'stock' => 'required|integer',
        'price1' => 'required|numeric',
        'price2' => 'required|numeric',
        'price3' => 'required|numeric',
    ];

    public static $errorMessages = [
        'category_id.required' => 'El ID de la categoria es requerido.',
        'category_id.integer' => 'El ID de la categoria debe ser de tipo integer.',
        'category_id.exists' => 'El ID de categories debe existir.',

        'name.required' => 'El nombre del producto es requerido.',
        'name.string' => 'El nombre del producto debe ser de tipo string.',

        'code.required' => 'El código del producto es requerido.',
        'code.string' => 'El código del producto debe ser de tipo string.',

        'measurement.required' => 'La medida del producto es requerida.',
        'measurement.string' => 'La medida del producto debe ser de tipo string.',

        'mts.required' => 'Los Mts2 son requeridos.',
        'mts.numeric' => 'Los Mts2 deben ser de tipo numeric.',

        'stock.required' => 'El stock es requerido.',
        'stock.integer' => 'El stock debe ser de tipo integer.',

        'price1.required' => 'El precio de la lista 1 del producto es requerido.',
        'price1.numeric' => 'El precio de la lista 1 del producto debe ser de tipo numerico.',
        'price2.required' => 'El precio de la lista 2 del producto es requerido.',
        'price2.numeric' => 'El precio de la lista 2 del producto debe ser de tipo numerico.',
        'price3.required' => 'El precio de la lista 3 del producto es requerido.',
        'price3.numeric' => 'El precio de la lista 3 del producto debe ser de tipo numerico.',
    ];

    public function categories()
    {
        return $this->belongsTo('App\Models\Categories', 'category_id');
    }

    public function productions()
    {
        return $this->hasMany('App\Models\Production', 'product_id');
    }

    public function sales()
    {

        return $this->belongsToMany('App\Models\Sales', 'sales_products', 'product_id', 'sale_id')
            ->withPivot([
                'amount',
                'unit_price',
            ]);
    }

    public function supplies()
    {
        return $this->belongsToMany('App\Models\Supplies', 'products_supplies', 'product_id', 'supplie_id')
            ->withPivot([
                'supplie_amount',
                'created_at',
                'updated_at'
            ]);
    }
}
