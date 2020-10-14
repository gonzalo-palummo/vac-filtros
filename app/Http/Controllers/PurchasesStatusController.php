<?php

namespace App\Http\Controllers;

use App\Models\Purchases_status;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;


class PurchasesStatusController extends Controller
{

    public function index()
    {
        $purchases_status = Purchases_status::all();
        return response()->json($purchases_status);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Purchases_status $purchases_status)
    {
        //
    }


    public function edit(Purchases_status $purchases_status)
    {
        //
    }


    public function update(Request $request, Purchases_status $purchases_status)
    {
        //
    }


    public function destroy(Purchases_status $purchases_status)
    {
        //
    }
}
