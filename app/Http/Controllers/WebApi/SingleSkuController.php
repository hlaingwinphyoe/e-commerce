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

        $other_sku = $item->skus()->first();

        $sku = $item->skus()->firstOrCreate([
            'item_id' => $item->id
        ], [
            // 'code' => uniqid(),
            'item_name' => $item->name,
            'pure_price' => $item->pure_price,
            'currency_id' => $currency ? $currency->id : 1,
            'min_stock' => $item->min_stock
        ]);

        if ($other_sku && $other_sku->pricings()->count()) {
            foreach ($other_sku->pricings as $pricing) {
                $sku->pricings()->updateOrCreate([
                    'min_qty' => $pricing->min_qty
                ], [
                    'amt' => $pricing->amt,
                    'status_id' => $pricing->status_id,
                    'min_qty' => $pricing->min_qty,
                    'max_qty' => $pricing->max_qty,
                ]);
            }
        }

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
