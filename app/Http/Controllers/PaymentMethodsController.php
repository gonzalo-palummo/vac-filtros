<?php

namespace App\Http\Controllers;

use App\Models\Payment_methods;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;


class PaymentMethodsController extends Controller
{

    public function index()
    {
        $payment_methods = Payment_methods::all();
        return response()->json($payment_methods);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Payment_methods $payment_methods)
    {
        //
    }


    public function edit(Payment_methods $payment_methods)
    {
        //
    }


    public function update(Request $request, Payment_methods $payment_methods)
    {
        //
    }


    public function destroy(Payment_methods $payment_methods)
    {
        //
    }
}
