<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Client;
use Illuminate\Support\Facades\Validator;


class ClientsController extends Controller
{

    public function index()
    {
        $clients = Clients::all();
        return response()->json($clients);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $validation = Validator::make($data, Clients::$rules, Clients::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }
        Clients::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Has introducido el cliente correctamente.',
            'data' => $data
        ]);
    }


    public function show(Clients $clients)
    {
        //
    }


    public function edit(Clients $clients)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $data = $request->input();
        $client = Clients::findOrFail($id);
        $validation = Validator::make($data, Clients::$rules, Clients::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }
        $client->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Has editado el cliente correctamente.',
            'data' => $data
        ]);
    }


    public function destroy($id)
    {
        $client = Clients::findOrFail($id);
        $client->delete();
        return response()->json([
            'success' => true,
            'message' => 'Has borrado el cliente correctamente.'
        ]);
    }
}
