<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index(Request $request) //READ
    {
        $countes = City::all();
        return response()->json([
            'data' => $countes,
        ]);
    }

    public function store(Request $request) //CREATE
    {
        $county = City::create($request->all());

        return response()->json([
            'data' => $county,
        ]);
    }

    public function destroy(Request $request, $id) //DESTROY
    {
        $county = City::findOrFail($id);
        $county->delete();

        return response()->json([
            'data' => $county,
        ]);
    }

    public function update(Request $request, $id) //UPDATE
    {
        $county = City::findOrFail($id);
        $county->update($request->all());

        return response()->json([
            'data' => $county,
        ]);
    }
}
