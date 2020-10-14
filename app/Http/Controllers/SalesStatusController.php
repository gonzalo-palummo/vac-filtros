<?php

namespace App\Http\Controllers;

use App\Models\Sales_status;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;


class SalesStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales_status = Sales_status::all();
        return response()->json($sales_status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sales_status  $sales_status
     * @return \Illuminate\Http\Response
     */
    public function show(Sales_status $sales_status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sales_status  $sales_status
     * @return \Illuminate\Http\Response
     */
    public function edit(Sales_status $sales_status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sales_status  $sales_status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sales_status $sales_status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sales_status  $sales_status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sales_status $sales_status)
    {
        //
    }
}
