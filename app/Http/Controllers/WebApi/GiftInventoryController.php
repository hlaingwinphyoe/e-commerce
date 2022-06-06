<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gift;
use App\Models\GiftInventory;

class GiftInventoryController extends Controller
{
    public function store(Request $request)
    {
        $gift = Gift::find($request->gift_id);

        $gift_inventory = $gift->inventories()->create([
            'qty' => $request->qty,
            'date' => $request->date ? $request->date : now(),
            'user_id' => auth()->user()->id
        ]);

        // $inventories = GiftInventory::select(['id', 'gift_id', 'qty', 'date'])->today()->with(['gift' => function($q){
        //     $q->select(['id', 'name']);
        // }])->latest()->get();

        $inventory = GiftInventory::select(['id', 'gift_id', 'qty', 'date'])->with(['gift' => function($q){
                $q->select(['id', 'name']);
            }])->find($gift_inventory->id);

        return response()->json($inventory);
    }

    public function update(Request $request, $id)
    {
        $gift_inventory = GiftInventory::find($id);

        $gift_inventory->update([
            'qty' => $request->qty ? $request->qty : $gift_inventory->qty,
            'date' => $request->date ? $request->date : $gift_inventory->date,
            'user_id' => auth()->user()->id
        ]);

        $inventories = GiftInventory::select(['id', 'gift_id', 'qty', 'date'])->today()->with(['gift' => function($q){
            $q->select(['id', 'name']);
        }])->latest()->get();

        return response()->json($inventories);
    }

    public function close($id)
    {
        $gift_inventory = GiftInventory::find($id);

        $gift_inventory->update([
            'is_published' => 1
        ]);

        $gift_inventory->gift->update(['stock' => $gift_inventory->gift->stock + $gift_inventory->qty]);

        $inventories = GiftInventory::select(['id', 'gift_id', 'qty', 'date'])->today()->with(['gift' => function($q){
            $q->select(['id', 'name']);
        }])->latest()->get();

        return response()->json($inventories);
    }
}
