<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable = [
        'name', 'cuit', 'address', 'telephone', 'email', 'price_list'
    ];

    public static $rules = [
        'name' => 'required|string',
        'cuit' => 'required|numeric',
        'address' => 'required|string',
        'telephone' => 'required|numeric',
        'email' => 'required|string',
    ];


    public static $errorMessages = [
        'name.required' => 'El nombre del cliente es requerido.',
        'name.string' => 'El nombre del cliente debe ser de tipo string.',
        'cuit.required' => 'El cuit del cliente es requerido.',
        'cuit.numeric' => 'El cuit del cliente debe ser de tipo numerico.',
        'address.required' => 'La direccion del cliente es requerida.',
        'address.string' => 'La direccion del cliente debe ser de tipo string.',
        'telephone.required' => 'El telefono del cliente es requerido.',
        'telephone.numeric' => 'El telefono del cliente debe ser de tipo numerico.',
        'email.required' => 'El email del cliente es requerido.',
        'email.string' => 'El email del cliente debe ser de tipo string.',
    ];


    public function sales()
    {
        return $this->hasMany('App\Models\Sales', 'paises_codigo');
    }
}
