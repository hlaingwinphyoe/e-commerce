<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::filterOn()->latest()->paginate(20);

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
        $inventory_month = Carbon::now()->format('ym');

        $latest_inventory = Inventory::where('inventory_month', intval($inventory_month))->orderBy('inventory_no', 'desc')->first();

        $inventory_no = $latest_inventory ? $latest_inventory->inventory_no + 1 : intval($inventory_month . '00001');

        $inventory = Inventory::create([
            'inventory_no' => $inventory_no,
            'inventory_month' => $inventory_month,
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

    public function print($id)
    {
        $inventory = Inventory::findOrFail($id);

        return view('admin.inventories.print')->with([
            'inventory' => $inventory
        ]);
    }
}
