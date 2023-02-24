<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Sku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkuController extends Controller
{
    public function index()
    {
        $skus = Sku::filterOn()->whereHas('item')->with('item')->inRandomOrder()->paginate(20);

        return response()->json($skus);
    }

    public function getAttributes($id)
    {
        $sku = Sku::find($id);

        return response()->json($sku->item->attributes);
    }

    public function getVariants($id)
    {
        $sku = Sku::find($id);

        return response()->json($sku->variants()->with(['attribute.values'])->get());
    }

    public function destroy($id)
    {
        $sku = Sku::findOrFail($id);

        $sku = DB::transaction(function () use ($sku) {
            foreach ($sku->variants as $variant) {
                $attribute = $variant->attribute;
                $value = $variant->value;
                $variant->delete();
                if (!$attribute->variants()->count()) {
                    $attribute->delete();
                }
                if (!$value->variants()->count()) {
                    $value->delete();
                }
            }

            $sku->delete();
            return $sku;
        });

        return response()->json($sku);
    }

    public function getPopular()
    {
        // $skus = Sku::where('stock', '>', 0)->whereHas('item', function ($q) {
        //     $q->where('deleted_at', null);
        // })->selectRaw('
        //     skus.id, COUNT(skus.id) as sku_count
        // ')->join('order_sku', 'skus.id', '=', 'order_sku.sku_id')
        //     ->join('orders', 'orders.id', '=', 'order_sku.order_id')
        //     ->groupBy('skus.id')
        //     ->orderBy('sku_count', 'desc')->take(15)->get();

        // $skus_id = [];

        // foreach ($skus as $sku) {
        //     array_push($skus_id, $sku->id);
        // }

        // if (count($skus_id) > 20) {
        //     $skus = Sku::with('item')->whereIn('id', $skus_id)->paginate(16);
        // } else {
        //     $skus = Sku::with('item')->inRandomOrder()->paginate(16);
        // }

        $skus = Sku::whereHas('item')->with('item')->inRandomOrder()->paginate(20);

        return response()->json($skus);

        // $skus = Sku::with(['item'])->latest()->take(20)->get();

        // return response()->json($skus);
    }
}
