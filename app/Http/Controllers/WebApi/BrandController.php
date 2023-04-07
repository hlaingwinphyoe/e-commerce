<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->get();

        return response()->json($brands);
    }

    public function store(Request $request)
    {

        $brand = Brand::firstOrCreate(
        [
            'name' => ucwords($request->q)
        ],
        [
            'slug' => Str::slug($request->q),
            'name' => ucwords($request->q),
            'user_id' => auth()->user()->id
        ]);

        return response()->json($brand);
    }

}
