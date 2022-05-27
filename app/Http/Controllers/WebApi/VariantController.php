<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VariantController extends Controller
{
    public function destroy($id)
    {
        $variant = Variant::findOrFail($id);

        $sku = DB::transaction(function () use ($variant) {

            $value = $variant->value;

            $attribute = $variant->attribute;

            $sku = $variant->sku;

            $variant->delete();

            $value->variants()->count() == 0 ? $value->delete() : '';

            $attribute->variants()->count() == 0 && $attribute->values()->count() == 0 ? $attribute->delete() : '';

            if ($sku->variants()->count()) {
                //update sku-name
                $name = '';
                foreach ($sku->variants as $index => $vari) {
                    $name .= $index == 0 ? $vari->value->name : ', ' . $vari->value->name;

                    if ($sku->variants()->count() == 1) {

                        $variants = Variant::where('sku_id', '!=', $vari->sku_id)
                            ->where('item_id', $vari->item_id)
                            ->where('value_id', $vari->value_id)
                            ->where('attribute_id', $vari->attribute_id)
                            ->get();

                        foreach ($variants as $v) {
                            if ($v->sku->variants()->count() == 1) {
                                $media = $v->sku->medias()->first();
                                if ($media) {
                                    Storage::exists($media->url) ? Storage::delete($media->url) : '';

                                    Storage::exists('public/thumbnail/' . $media->slug) ? Storage::delete('public/thumbnail/' . $media->slug) : '';

                                    $media->delete();
                                }
                                $v->delete();
                                $v->sku->delete();
                            }
                        }
                    }
                }
                $sku->update(['data' => $name]);
            } else {
                $sku->delete();
            }
            return $sku;
        });

        $item = Item::find($variant->item_id);

        $skus = $item->skus()->with(['pricings.role', 'medias', 'variants.attribute.values'])->get();

        return response()->json(['skus' => $skus, 'variant_id' => $variant->id, 'sku_name' => $sku->data, 'attribute' => $variant->attribute ]);
    }
}
