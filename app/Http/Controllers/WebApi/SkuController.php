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
        $skus = Sku::filterOn()->orderBy('stock')->get();

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
}
