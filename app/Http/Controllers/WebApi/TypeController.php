<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::filterOn()->get();

        return response()->json($types);
    }

    public function store(Request $request)
    {
        $type = Type::firstOrCreate([
            'name' => ucwords($request->q)
        ],[
            'slug' => Str::slug($request->q),
            'name' => ucwords($request->q),
            'user_id' => auth()->user()->id,
        ]);

        return response()->json($type);
    }
}
