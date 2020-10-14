<?php

namespace App\Http\Controllers;

use App\Models\Measure;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;


class MeasureController extends Controller
{

    public function index()
    {
        $measure = Measure::all();
        return response()->json($measure);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Measure $measure)
    {
        //
    }

    public function edit(Measure $measure)
    {
        //
    }


    public function update(Request $request, Measure $measure)
    {
        //
    }

    public function destroy(Measure $measure)
    {
        //
    }
}
