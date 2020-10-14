<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Providers extends Model
{
    protected $fillable = [
        'name', 'cuit', 'address', 'telephone', 'email'
    ];

    public static $rules = [
        'name' => 'required|string',
        'cuit' => 'required|numeric',
        'address' => 'required|string',
        'telephone' => 'required|numeric',
        'email' => 'required|string',
    ];


    public static $errorMessages = [
        'name.required' => 'El nombre del proveedor es requerido.',
        'name.string' => 'El nombre del proveedor debe ser de tipo string.',
        'cuit.required' => 'El cuit del proveedor es requerido.',
        'cuit.numeric' => 'El cuit del proveedor debe ser de tipo numerico.',
        'address.required' => 'La direccion del proveedor es requerida.',
        'address.string' => 'La direccion del proveedor debe ser de tipo string.',
        'telephone.required' => 'El telefono del proveedor es requerido.',
        'telephone.numeric' => 'El telefono del proveedor debe ser de tipo numerico.',
        'email.required' => 'El email del proveedor es requerido.',
        'email.string' => 'El email del proveedor debe ser de tipo string.',
    ];



    public function purchases()
    {

        return $this->hasMany('App\Models\Purchases', 'provider_id');
    }
}
