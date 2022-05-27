<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class ItemAttributeController extends Controller
{
    public function index($id)
    {
        $attributes = Attribute::where('item_id', $id)->with(['values'])->get();

        return response()->json($attributes);
    }
}
