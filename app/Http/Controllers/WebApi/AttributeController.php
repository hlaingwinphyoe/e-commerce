<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Item;
use App\Models\Sku;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function store(Request $request)
    {
        $item = Item::findOrFail($request->item_id);

        $parent_id = $item->main_attribute() ? $item->main_attribute()->id : 0;

        $attribute = Attribute::firstOrcreate([
            'name' => ucwords($request->attr_name),
            'item_id' => $request->item_id
        ],[
            'name' => ucwords($request->attr_name),
            'item_id' => $request->item_id,
            'parent_id' => $parent_id
        ]);

        if(!$request->already_status) {
            $status = Status::firstOrCreate([
                'slug' => Str::slug($request->attr_name)
            ],[
                'slug' => Str::slug($request->attr_name),
                'name' => ucwords($request->attr_name),
                'type' => 'attribute'
            ]);
        }

        $attributes = Attribute::where('item_id', $request->item_id)->with(['values'])->get();

        return response()->json($attributes);
    }

    public function addAttributeValue(Request $request)
    {
        $attribute = Attribute::find($request->attribute_id);

        $value = $attribute->values()->where('name', $request->name)->first();

        if(!$value) {
            $value = $attribute->values()->create([
                'name' => ucwords($request->name),
            ]);
        }

        $attribute->variants()->create([
            'item_id' => $request->item_id,
            'sku_id' => $request->sku_id,
            'value_id' => $value->id
        ]);

        $sku = Sku::find($request->sku_id);

        $sku->update(['data' => $sku->data . ', '. ucwords($request->name)]);

        $data = [
            'attribute_id' => $attribute->id,
            'sku_name' => $sku->data
        ];

        return response()->json($data);

    }

    public function update(Request $request, $id)
    {
        $attribute = Attribute::find($id);

        $attribute->update([
            'name' => ucwords($request->name)
        ]);

        return response()->json($attribute);
    } 

    public function destroy($id) 
    {
        $attribute = Attribute::find($id);

        if($attribute->sub_attributes()->count()) {
            foreach($attribute->sub_attributes as $index => $sub_attr) {
                if($index == 0 ) {
                    $parent_id = $sub_attr->id;
                    $sub_attr->update(['parent_id' => 0]);
                }else {
                    $sub_attr->update(['parent_id' => $parent_id]);
                }
            }
        }

        $attribute->delete();

        $attributes = Attribute::where('item_id', $attribute->item_id)->with(['values'])->get();

        return response()->json($attributes);
    }
}
