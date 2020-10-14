<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Sales extends Model
{
    protected $fillable = [
        'client_id', 'sale_status_id', 'payment_method_id', 'invoice_number', 'invoice_date', 'payment_date',
        'status_changed_date', 'total_price'
    ];

    public static $rules = [
        'client_id' => 'required|integer|exists:clients,id',
        'sale_status_id' => 'required|integer|exists:sales_statuses,id',
        'payment_method_id' => 'required|integer|exists:payment_methods,id',
        'invoice_number' => 'required|numeric',
        'invoice_date' => 'required|date',
        'payment_date' => 'required|date',
        'status_changed_date' => 'date',
        'total_price' => 'required|numeric',

        //To validate in array products input.
        'products.*.product_id' => 'required|integer|exists:products,id',
        'products.*.amount' => 'required|integer|min:1',
        'products.*.unit_price' => 'required|numeric'
    ];

    public static $errorMessages = [
        'client_id.required' => 'El ID del cliente es requerido.',
        'client_id.integer' => 'El ID del cliente debe ser de tipo integer.',
        'client_id.exists' => 'El ID de clients debe existir.',
        'sale_status_id.required' => 'El ID del estado de la venta es requerido.',
        'sale_status_id.integer' => 'El ID del estado de la venta debe ser de tipo integer.',
        'sale_status_id.exists' => 'El ID de sale_statuses debe existir.',
        'payment_method_id.required' => 'El ID del metodo de pago es requerido.',
        'payment_method_id.integer' => 'El ID del metodo de pago debe ser de tipo integer.',
        'payment_method_id.exists' => 'El ID de payment_methods debe existir.',
        'invoice_number.required' => 'La venta debe tener un numero de factura.',
        'invoice_number.numeric' => 'El numero de factura debe ser un numero.',
        'total_price.required' => 'La venta debe tener un precio total.',
        'total_price.numeric' => 'El precio total debe ser un numero.',
        'invoice_date.required' => 'La venta debe tener la fecha de la factura.',
        'invoice_date.date' => 'La fecha de la factura debe ser una fecha.',
        'payment_date.required' => 'La venta debe tener el dia que se pagó.',
        'payment_date.date' => 'El día que se pago debe ser una fecha.',

        'products.*.product_id.required' => 'sales_products debe tener un product_id.',
        'products.*.product_id.integer' => 'El sale debe ser un ID.',
        'products.*.product_id.exists' => 'El ID de products debe existir.',
        'products.*.amount.required' => 'sales_products debe tener un monto.',
        'products.*.amount.integer' => 'El monto debe ser un numero.',
        'products.*.amount.min' => 'La cantidad minima debe ser de 1.',
        'products.*.unit_price.required' => 'sales_products debe tener un precio unitario.',
        'products.*.unit_price.integer' => 'El precio unitario debe ser un número.'
    ];

    public function sales_status()
    {
        return $this->belongsTo('App\Models\Sales_status', 'sale_status_id');
    }

    public function payment_methods()
    {
        return $this->belongsTo('App\Models\Payment_methods', 'payment_method_id');
    }

    public function clients()
    {
        return $this->belongsTo('App\Models\Clients', 'client_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Products', 'sales_products', 'sale_id', 'product_id')
            ->withPivot([
                'amount',
                'unit_price',
                'created_at',
                'updated_at'
            ]);
    }
}
