<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('name')->get();

        return response()->json($units);
    }

    public function store(Request $request)
    {

        $unit = Unit::where('name', $request->q)->first();

        if($unit){
            $request->validate([
                'q' => 'required|unique:units,name,'.$unit->id,
            ]);
        }else{
            $request->validate([
                'q' => 'required|unique:units,name',
            ]);
        }

        $unit = Unit::updateOrCreate(
        [
            'name' => $request->q
        ],
        [
            'slug' => Str::slug($request->q),
            'name' => $request->q,
        ]);

        return response()->json($unit);
    }
}
