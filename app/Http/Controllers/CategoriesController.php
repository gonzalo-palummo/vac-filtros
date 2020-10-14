<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;


class CategoriesController extends Controller
{

    public function index()
    {
        $categories = Categories::all();
        return response()->json($categories);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Categories $categories)
    {
        //
    }


    public function edit(Categories $categories)
    {
        //
    }


    public function update(Request $request, Categories $categories)
    {
        //
    }


    public function destroy($id)
    {
        $categorie = Categories::findOrFail($id);
        $categorie->delete();
        return response()->json([
            'success' => true,
            'message' => 'Has borrado la categoria correctamente.'
        ]);
    }
}
