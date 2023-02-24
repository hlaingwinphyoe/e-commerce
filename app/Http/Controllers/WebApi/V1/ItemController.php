<?php

namespace App\Http\Controllers\WebApi\V1;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        $item->update(['sku_control' => $request->sku_control]);

        return response()->json($item);
    }
}
