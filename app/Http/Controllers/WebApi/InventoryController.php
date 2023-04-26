<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function create()
    {
        // $inventory = Inventory::whereDoesntHave('supplier')->whereDate('date', now())->first();

        // if(!$inventory) {
        //     $inventory = Inventory::create([
        //         'date' => now()
        //     ]);
        // }

        // $inventory = Inventory::create([
        //     'date' => now()
        // ]);

        $inventory = Inventory::latest()->first();

        return response()->json($inventory);
    }

    public function inventoryUpdate(Request $request,$id)
    {
        $inventory = Inventory::with('skus')->findOrFail($id);
        $inventory->update([
            'date' => $request->date ?? now(),
            'type' => $request->type,
            'desc' => $request->desc,
            'user_id' => auth()->user()->id
        ]);

        return response()->json($inventory);
    }

    public function update(Request $request,$id)
    {
        $inventory = Inventory::find($id);

        $inventory->update([
            'supplier_id' => $request->supplier,
            'date' => $request->date,
            'is_published' => 1
        ]);

        return response()->json($inventory);
    }
    
}
