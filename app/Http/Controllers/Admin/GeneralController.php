<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function index()
    {
        $generals = Inventory::isType('general')->latest()->paginate(20);
        return view('admin.generals.index',compact('generals'));
    }


    public function create()
    {
        return view('admin.generals.create');
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
            'type' => $request->type,
            'desc' => $request->desc,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('admin.generals.edit', $inventory->id)->with('message', 'Successfully Created.');
    }

    public function show($id)
    {
        $general = Inventory::findOrFail($id);
    }


    public function edit($id)
    {
        $general = Inventory::findOrFail($id);
        return view('admin.generals.edit',compact('general'));
    }

    public function print($id)
    {
        # code...
    }

}
