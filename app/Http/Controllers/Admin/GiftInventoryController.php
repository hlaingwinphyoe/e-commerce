<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GiftInventory;
use App\Models\Gift;

class GiftInventoryController extends Controller
{
    public function index()
    {
        $gift_inventories = GiftInventory::filterOn()->latest()->paginate(20);

        return view('admin.gift-inventory.index')->with([
            'gift_inventories' => $gift_inventories
        ]);
    }

    public function create()
    {
        $gift_id = request('id') ? request('id') : '';

        $inventories = GiftInventory::select(['id', 'gift_id', 'qty', 'date'])->today()->with(['gift' => function($q){
            $q->select(['id','name']);
        }])->latest()->get();

        return view('admin.gift-inventory.create')->with([
            'gift_id' => $gift_id,
            'inventories' => $inventories
        ]);
    }

    public function close($id)
    {
        $gift_inventory = GiftInventory::find($id);

        $gift_inventory->gift->update(['stock' => $gift_inventory->gift->stock + $gift_inventory->qty]);

        $gift_inventory->update([
            'is_published' => 1
        ]);

        return redirect(request()->session()->get('prev_route'))->with('message', 'Update Inventory Status');
        
    }

    public function destroy($id)
    {
        $gift_inventory = GiftInventory::find($id);

        if($gift_inventory->is_published && auth()->user()->role->slug != 'technician') {
            return redirect()->back()->with('error', 'Cannot Delete this inventory');
        }

        $gift_inventory->gift->update(['stock' => $gift_inventory->gift->stock - $gift_inventory->qty]);

        $gift_inventory->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Delete Inventory');
    }
}
