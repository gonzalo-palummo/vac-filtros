<?php

namespace App\Http\Controllers;

use App\Models\Supplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SuppliesController extends Controller
{

    public function index()
    {
        $supplies = Supplies::with(['measures'])->get();
        return response()->json($supplies);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $validation = Validator::make($data, Supplies::$rules, Supplies::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }
        Supplies::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Has introducido el insumo correctamente.',
            'data' => $data
        ]);
    }


    public function show(Supplies $supplies)
    {
        //
    }


    public function edit(Supplies $supplies)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $data = $request->input();
        $supplie = Supplies::findOrFail($id);
        $validation = Validator::make($data, Supplies::$rules, Supplies::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }
        $supplie->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Has editado el insumo correctamente.',
            'data' => $data
        ]);
    }


    public function destroy($id)
    {
        $supplie = Supplies::findOrFail($id);
        $supplie->delete();
        return response()->json([
            'success' => true,
            'message' => 'Has borrado el insumo correctamente.'
        ]);
    }
}
