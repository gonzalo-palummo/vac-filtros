<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Supplies extends Model
{

    protected $appends = ['last_purchase_date'];

    public function getLastPurchaseDateAttribute()
    {
        return DB::table('purchases_supplies')->where('supplie_id', '=', $this->id)->orderBy('created_at')->get()->last();
    }

    protected $fillable = [
        'measure_id', 'code', 'name', 'stock'//, 'last_price'
    ];

    public static $rules = [
        'measure_id' => 'required|integer|exists:measures,id',
        'code' => 'required|string',
        'name' => 'required|string',
        'stock' => 'required|numeric',
        //'last_price' => 'required|numeric',
    ];

    public static $errorMessages = [
        'measure_id.required' => 'El ID de la medida es requerido.',
        'measure_id.integer' => 'El ID de la medida debe ser de tipo integer.',
        'measure_id.exists' => 'El ID de measures debe existir.',
        'name.required' => 'El nombre del insumo es requerido.',
        'name.string' => 'El nombre del insumo debe ser de tipo string.',
        'stock.required' => 'El stock del insumo es requerido.',
        'stock.numeric' => 'El stock del insumo debe ser de tipo numerico.',
        // 'last_price.required' => 'El ultimo precio del insumo es requerido.',
        // 'last_price.numeric' => 'El ultimo precio del insumo debe ser de tipo numerico.',
    ];


    public $timestamps = true;

    public function products()
    {
        return $this->belongsToMany('App\Models\Products', 'products_supplies', 'supplie_id', 'product_id')
            ->withPivot([
                'supplie_amount',
                'created_at',
                'updated_at'
            ]);
    }

    public function measures()
    {
        return $this->belongsTo('App\Models\Measure', 'measure_id');
    }

    public function purchases()
    {
        return $this->belongsToMany('App\Models\Purchases', 'purchases_supplies', 'supplie_id', 'purchase_id')
            ->withPivot([
                'amount',
                'unit_price',
            ]);
    }
}
