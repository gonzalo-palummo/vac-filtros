<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductsController extends Controller
{
    public function index()
    {
        $products = Products::with(['productions', 'supplies.measures', 'categories'])->get();
        return response()->json($products);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validation = Validator::make($data, Products::$rules, Products::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }

        $last_product = Products::create($data);
        $product = Products::find($last_product->id);

        foreach ($request->input('supplies') as $supplie) {
            $product->supplies()->attach($supplie['supplie_id'], [
                'supplie_amount' => $supplie['supplie_amount'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Has introducido el producto correctamente.',
            'data' => $data
        ]);
    }


    public function show(Products $products)
    {
        //
    }


    public function edit(Products $products)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $data = $request->input();
        $product = Products::findOrFail($id);
        $validation = Validator::make($data, Products::$rules, Products::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }
        $product->update($data);

        $product->supplies()->detach();

        foreach ($request->input('supplies') as $supplie) {
            $product->supplies()->attach($supplie['supplie_id'], [
                'supplie_amount' => $supplie['supplie_amount'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Has editado el producto correctamente.',
            'data' => $data
        ]);
    }


    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();
        return response()->json([
            'success' => true,
            'message' => 'Has borrado el producto correctamente.'
        ]);
    }
}
