<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Models\Sku;
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

    public function getSkus($id)
    {
        $skus = Sku::whereHas('item', function($q) use($id) {
            $q->whereHas('types', function($q) use($id) {
                $q->where('id', $id);
            });
        })->with('item')->paginate(16);

        return response()->json($skus);
    }
}
