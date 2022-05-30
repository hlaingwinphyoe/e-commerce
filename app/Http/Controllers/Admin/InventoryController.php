<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::latest()->paginate(20);

        return view('admin.inventories.index')->with([
            'inventories' => $inventories
        ]);
    }

    public function create() 
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.inventories.create')->with([
            'suppliers' => $suppliers
        ]);
    }

    public function store(Request $request)
    {
        $inventory = Inventory::create([
            'supplier_id' => $request->supplier_id,
            'date' => $request->date ?? now(),
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('admin.inventories.edit', $inventory->id)->with('message', 'Successfully Created.');
    }

    public function edit($id)
    {
        $inventory = Inventory::find($id);

        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.inventories.edit')->with([
            'inventory' => $inventory,
            'suppliers' => $suppliers
        ]);

    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::find($id);

        $inventory->update([
            'supplier_id' => $request->supplier_id,
            'date' => $request->date ?? $inventory->date
        ]);

        return redirect()->route('admin.inventories.edit', $inventory->id)->with('message', 'Successfully updated.');
    }

    public function show($id)
    {
        $inventory = Inventory::find($id);

        return view('admin.inventories.show')->with([
            'inventory' => $inventory,
        ]);

    }
}
