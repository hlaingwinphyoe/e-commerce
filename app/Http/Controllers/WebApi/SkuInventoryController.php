<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Inventory;
use App\Models\Sku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkuInventoryController extends Controller
{
    public function store(Request $request, $id)
    {
        $inventory = Inventory::find($id);

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
                    'currency_id' => $currency ? $currency->id : 1
                ]]);
                $new_qty = $request->qty;
            }

            $org_sku = Sku::find($request->sku);

            //update sku->stock
            $org_sku->update(['stock' => $org_sku->stock + $new_qty - $old_qty]);

            return $sku;
        });

        return response()->json([
            'sku' => $sku,
            'skus' => $inventory->skus
        ]);
    }

    public function delete($inventory, $sku)
    {
        $inventory = Inventory::find($inventory);

        $sku = DB::transaction(function () use ($inventory, $sku) {            

            $sku_pivot = $inventory->skus()->where('sku_id', $sku)->first();

            $sku = Sku::find($sku);

            $sku->update(['stock' => $sku->stock - $sku_pivot->pivot->qty]);

            $inventory->skus()->detach($sku->id);

            return $sku;
        });

        return response()->json([
            'sku' => $sku,
            'skus' => $inventory->skus
        ]);

        // return response()->json($sku);
    }
}
