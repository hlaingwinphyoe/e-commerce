<?php

namespace App\Http\Controllers\WebApi\V1;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Pricing;
use App\Models\Status;
use Illuminate\Http\Request;

class ItemPricingController extends Controller
{
    public function index($id)
    {
        $item = Item::find($id);

        $pricings = [];

        if ($item->skus()->count()) {
            $sku = $item->skus()->first();
            $pricings = $sku && $sku->pricings ? Pricing::whereIn('id', $sku->pricings()->pluck('id'))->get() : [];
        }

        return response()->json($pricings);
    }

    public function store(Request $request, $id)
    {
        $item = Item::find($id);

        $status = Status::where('slug', 'fixed')->first();

        foreach ($item->skus as $sku) {
            $sku->pricings()->updateOrCreate([
                'min_qty' => $request->min_qty ?? 1,
            ], [
                'amt' => $request->price,
                'status_id' => $status ? $status->id : 10,
                'min_qty' => $request->min_qty ?? 1,
                'max_qty' => $request->max_qty ?? 1,
            ]);
        }

        $skus = $item->skus()->with(['pricings', 'medias', 'variants.attribute.values'])->get();

        return response()->json($item->skus);
    }
}
