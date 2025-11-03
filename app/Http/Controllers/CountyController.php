<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    public function index(Request $request) //READ
    {
        $countes = County::all();
        return response()->json([
            'data' => $countes,
        ]);
    }

    public function store(Request $request) //CREATE
    {
        $county = County::create($request->all());

        return response()->json([
            'data' => $county,
        ]);
    }

    public function destroy(Request $request, $id) //DESTROY
    {
        $county = County::findOrFail($id);
        $county->delete();

        return response()->json([
            'data' => $county,
        ]);
    }

    public function update(Request $request, $id) //UPDATE
    {
        $county = County::findOrFail($id);
        $county->update($request->all());

        /*if (!$county) {
            return response()->json(['message' => 'Not found!'], 404);
        }*/

        return response()->json([
            'data' => $county,
        ]);
    }
}
