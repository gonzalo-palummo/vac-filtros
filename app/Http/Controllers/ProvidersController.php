<?php

namespace App\Http\Controllers;

use App\Models\Providers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProvidersController extends Controller
{

    public function index()
    {
        $providers = Providers::all();
        return response()->json($providers);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $validation = Validator::make($data, Providers::$rules, Providers::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }
        Providers::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Has introducido el proveedor correctamente.',
            'data' => $data
        ]);
    }


    public function show(Providers $providers)
    {
        //
    }


    public function edit(Providers $providers)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $data = $request->input();
        $provider = Providers::findOrFail($id);
        $validation = Validator::make($data, Providers::$rules, Providers::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }
        $provider->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Has editado el proveedor correctamente.',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $provider = Providers::findOrFail($id);
        $provider->delete();
        return response()->json([
            'success' => true,
            'message' => 'Has borrado el proveedor correctamente.'
        ]);
    }
}
