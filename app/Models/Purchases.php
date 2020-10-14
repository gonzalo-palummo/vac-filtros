<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Purchases extends Model
{
    /*
    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->format('d-m-Y');
    }
    */

    protected $fillable = [
        'provider_id', 'purchase_status_id', 'payment_method_id', 'invoice_number', 'price', 'invoice_date',
        'payment_date', 'status_changed_date'
    ];

    public static $rules = [
        'provider_id' => 'required|integer|exists:providers,id',
        'purchase_status_id' => 'required|integer|exists:purchases_statuses,id',
        'payment_method_id' => 'required|integer|exists:payment_methods,id',
        'invoice_number' => 'required|numeric',
        'price' => 'required|numeric',
        'invoice_date' => 'required|date',
        'payment_date' => 'required|date',
        'status_changed_date' => 'date',

        //To validate in array supplies input.
        'supplies.*.supplie_id' => 'required|integer|exists:supplies,id',
        'supplies.*.amount' => 'required|integer',
        'supplies.*.unit_price' => 'required|numeric'
    ];


    public static $errorMessages = [
        'provider_id.required' => 'El campo provider_id es requerido.',
        'provider_id.integer' => 'El provider debe ser un ID.',
        'provider_id.exists' => 'El ID de providers debe existir.',
        'purchase_status_id.required' => 'El campo purchase_status_id es requerido.',
        'purchase_status_id.integer' => 'El purchase status debe ser un ID.',
        'purchase_status_id.exists' => 'El ID de purchase_statuses debe existir.',
        'payment_method_id.required' => 'El campo paymen_method_id es requerido.',
        'payment_method_id.integer' => 'El payment method debe ser un ID.',
        'payment_method_id.exists' => 'El ID de payment_methods debe existir.',
        'invoice_number.required' => 'La compra debe tener un numero de factura',
        'invoice_number.numeric' => 'El numero de factura debe ser un numero',
        'price.required' => 'La compra debe tener un precio',
        'price.numeric' => 'El precio debe ser un numero',
        'invoice_date.required' => 'La compra debe tener la fecha de la factura',
        'invoice_date.date' => 'La fecha de la factura debe ser una fecha',
        'payment_date.required' => 'La compra debe tener el dia que se pagó',
        'payment_date.date' => 'El día que se pago debe ser una fecha',

        'supplies.*.supplie_id.required' => 'Purchases_supplie debe tener un supplie_id',
        'supplies.*.supplie_id.integer' => 'El supplie debe ser un ID',
        'supplies.*.supplie_id.exists' => 'El ID de supplies debe existir.',
        'supplies.*.amount.required' => 'Purchases_supplie debe tener un monto',
        'supplies.*.amount.integer' => 'El monto debe ser un numero',
        'supplies.*.unit_price.required' => 'Purchases_supplie debe tener un precio unitario',
        'supplies.*.unit_price.integer' => 'El precio unitario debe ser un número'
    ];


    public function payment_methods()
    {
        return $this->belongsTo('App\Models\Payment_methods', 'payment_method_id');
    }

    public function purchases_status()
    {
        return $this->belongsTo('App\Models\Purchases_status', 'purchase_status_id');
    }

    public function providers()
    {
        return $this->belongsTo('App\Models\Providers', 'provider_id');
    }

    public function supplies()
    {
        return $this->belongsToMany('App\Models\Supplies', 'purchases_supplies', 'purchase_id', 'supplie_id')
            ->withPivot([
                'amount',
                'unit_price',
                'created_at',
                'updated_at'
            ]);
    }
}
