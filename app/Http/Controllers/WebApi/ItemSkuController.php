<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Item;
use App\Models\Sku;
use Illuminate\Http\Request;

class ItemSkuController extends Controller
{
    public function index($id)
    {
        $skus = Sku::where('item_id', $id)->with(['pricings', 'medias', 'variants.attribute.values'])->get();

        return response()->json($skus);
    }

    public function store(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $currency = Currency::where('slug', 'mmk')->first();

        $attrs = [];

        if ($item->main_attribute()) {

            foreach ($item->main_attribute()->values as $value) {
                $data = [
                    'attribute' => $value->attribute_id,
                    'value' => $value->id
                ];
                array_push($attrs, $data);
            }

            if ($item->main_attribute()->sub_attributes()->count()) {
                foreach ($item->main_attribute()->sub_attributes as $attribute) {
                    $new_attr = [];
                    foreach ($attribute->values as $value) {
                        $data = [
                            'attribute' => $value->attribute_id,
                            'value' => $value->id
                        ];
                        foreach ($attrs as $attr) {
                            if (count($attr) == count($attr, COUNT_RECURSIVE)) {
                                $ary = [
                                    $attr, $data
                                ];
                            } else {
                                $ary = $attr;
                                array_push($ary, $data);
                            }
                            array_push($new_attr, $ary);
                        }
                    }
                    $attrs = $new_attr;
                }
            }

            //create skus
            foreach ($attrs as $attr) {
                $sku = Sku::create([
                    // 'code' => uniqid(),
                    'pure_price' => 0,
                    'currency_id' => $currency ? $currency->id : 1,
                    'min_stock' => $item->min_stock,
                    'item_id' => $item->id
                ]);
                if (count($attr) == count($attr, COUNT_RECURSIVE)) {
                    //single attributes
                    $variant = $sku->variants()->create([
                        'item_id' => $item->id,
                        'attribute_id' => $attr['attribute'],
                        'value_id' => $attr['value']
                    ]);
                    $sku->update(['data' => $variant->value->name]);
                } else {
                    $name = '';
                    foreach ($attr as $index => $atr) {
                        //multi attributes
                        $variant = $sku->variants()->create([
                            'item_id' => $item->id,
                            'attribute_id' => $atr['attribute'],
                            'value_id' => $atr['value']
                        ]);
                        $name .= $index !== 0 ? ', ' : '';
                        $name .= $variant->value->name;
                    }
                    $sku->update(['data' => $name]);
                }
            }
        }

        $skus = $item->skus()->with(['pricings.role', 'medias', 'variants.attribute.values'])->get();

        $attributes = $item->attributes()->with('values')->get();

        return response()->json(['skus' => $skus, 'attributes' => $attributes]);
    }
}
