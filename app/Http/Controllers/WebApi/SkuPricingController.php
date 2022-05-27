<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Pricing;
use App\Models\Status;
use Illuminate\Http\Request;

class SkuPricingController extends Controller
{
    public function update(Request $request, $id)
    {
        $pricing = Pricing::find($id);

        $pricing->update([
            'amt' => $request->price,
            'min_qty' => $request->min_qty,
            'max_qty' => $request->max_qty,
        ]);

        return response()->json($pricing);
    }

    public function destroy($id)
    {
        $pricing = Pricing::findOrFail($id);

        $pricing->delete();

        return response()->json($pricing);
    }

    public function allPricings(Request $request, $id)
    {
        $item = Item::find($id);

        $status = Status::where('slug', 'fixed')->first();

        foreach ($item->skus as $sku) {
            $sku->pricings()->updateOrCreate([
                'min_qty' => $request->min_qty,
            ], [
                'amt' => $request->price,
                'status_id' => $status ? $status->id : 10,
                'min_qty' => $request->min_qty ?? 1,
                'max_qty' => $request->max_qty ?? $request->min_qty,
            ]);
        }

        $skus = $item->skus()->with(['pricings', 'medias', 'variants.attribute.values'])->get();

        return response()->json($skus);
    }
}
