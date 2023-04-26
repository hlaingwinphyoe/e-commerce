<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Inventory;
use App\Models\Sku;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkuInventoryController extends Controller
{
    public function store(Request $request, $id)
    {
        $inventory = Inventory::find($id);

        if (!$inventory) {
            $inventory_month = Carbon::now()->format('ym');

            $latest_inventory = Inventory::where('inventory_month', intval($inventory_month))->orderBy('inventory_no', 'desc')->first();

            $inventory_no = $latest_inventory ? $latest_inventory->inventory_no + 1 : intval($inventory_month . '00001');

            $inventory = Inventory::create([
                'inventory_month' => $inventory_month,
                'inventory_no' => $inventory_no,
                'date' => now(),
                'user_id' => auth()->user()->id,
                'is_published' => 1
            ]);
        }

        $sku = DB::transaction(function () use ($request, $inventory) {

            $currency = Currency::where('slug', 'mmk')->first();

            $sku = $inventory->skus()->where('sku_id', $request->sku)->first();

            $old_qty = 0;

            $new_qty = 0;

            if ($sku) {
                $old_qty = $sku->pivot->qty;
                $sku = $sku->pivot->update([
                    'qty' => $sku->pivot->qty + $request->qty,
                    'amount' => $request->amount
                ]);
                $new_qty = $old_qty + $request->qty;
            } else {
                $sku = $inventory->skus()->attach([$request->sku => [
                    'qty' => $request->qty,
                    'amount' => $request->amount,
                    'rate' => 1,
                    'currency_id' => $sku ? $sku->currency_id : 1
                ]]);
                $new_qty = $request->qty;
            }

            $org_sku = Sku::find($request->sku);

            //update sku->stock
            $org_sku->update(['stock' => $org_sku->stock + $new_qty - $old_qty]);

            return $sku;
        });

        $sku = Sku::find($request->sku);

        return response()->json([
            'sku' => $sku,
            'skus' => $inventory->skus
        ]);
    }

    public function update(Request $request,$inventory,$sku_id)
    {
        $inventory = Inventory::find($inventory);

        $sku = DB::transaction(function () use ($request, $inventory,$sku_id) {
            $sku = $inventory->skus()->where('sku_id', $sku_id)->first();

            $new_qty = 0;

            $old_qty = $sku->pivot->qty;

            $sku = $sku->pivot->update([
                'qty' => $request->qty,
                'amount' => $request->amount
            ]);

            $new_qty = $request->qty;

            $org_sku = Sku::find($request->sku);

            //update sku->stock
            $org_sku->update(['stock' => $org_sku->stock + $new_qty - $old_qty]);

            return $sku;
        });

        $sku = Sku::find($request->sku);

        return response()->json([
            'sku' => $sku,
            'skus' => $inventory->skus
        ]);
    }

    public function destroy($inventory, $sku_id)
    {
        $inventory = Inventory::find($inventory);

        $sku = DB::transaction(function () use ($inventory, $sku_id) {

            $sku_pivot = $inventory->skus()->where('sku_id', $sku_id)->first();

            $sku = Sku::find($sku_id);

            $sku->update(['stock' => $sku->stock - $sku_pivot->pivot->qty]);

            $inventory->skus()->detach($sku->id);

            return $sku;
        });

        $sku = Sku::find($sku_id);

        return response()->json([
            'sku' => $sku,
            'skus' => $inventory->skus
        ]);

        // return response()->json($sku);
    }
}
