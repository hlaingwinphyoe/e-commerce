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
        $unit = Unit::firstOrCreate(
        [
            'name' => ucwords($request->q)
        ],
        [
            'slug' => Str::slug($request->q),
            'name' => ucwords($request->q),
        ]);

        return response()->json($unit);
    }
}
