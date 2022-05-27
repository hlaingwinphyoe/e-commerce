<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Item;
use App\Models\Status;
use Illuminate\Http\Request;

class SingleSkuController extends Controller
{
    public function store(Request $request)
    {
        $item = Item::findOrFail($request->item_id);

        $currency = Currency::where('slug', 'mmk')->first();

        $status = Status::where('slug', 'fixed')->first();

        $sku = $item->skus()->firstOrCreate([
            'item_id' => $item->id
        ], [
            // 'code' => uniqid(),
            'item_name' => $item->name,
            'pure_price' => 0,
            'currency_id' => $currency ? $currency->id : 1,
            'min_stock' => $item->min_stock
        ]);

        $sku->pricings()->updateOrCreate([
            'min_qty' => $request->min_qty
        ], [
            'amt' => $request->price,
            'status_id' => $status ? $status->id : 10,
            'min_qty' => $request->min_qty ?? 1,
            'max_qty' => $request->max_qty ?? $request->min_qty,
        ]);

        $pricings = $sku->pricings()->get();

        return response()->json($pricings);
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if ($item->skus->count() === 1) {
            $sku = $item->skus()->first();

            $sku->pricings()->delete();

            $sku->delete();
        }

        return response()->json($item);
    }
}
