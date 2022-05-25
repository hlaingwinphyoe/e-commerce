<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Value;
use Illuminate\Http\Request;

class ValueController extends Controller
{
    public function singleStore(Request $request)
    {
        $value = Value::where('name', ucwords($request->name))->where('attribute_id', $request->attribute_id)->first();
        if (!$value) {

            $value = Value::updateOrCreate([
                'id' => $request->value_id,
            ], [
                'name' => ucwords($request->name),
                'attribute_id' => $request->attribute_id
            ]);
        }


        // $values = Value::where('attribute_id', $request->attribute_id)->get();

        return response()->json($value);
    }

    public function store(Request $request)
    {
        if ($request->name) {
            $value = Value::firstOrcreate([
                'name' => ucwords($request->name),
                'attribute_id' => $request->attribute_id
            ], [
                'name' => ucwords($request->name),
                'attribute_id' => $request->attribute_id
            ]);
        }

        $values = Value::where('attribute_id', $request->attribute_id)->get();

        return response()->json($values);
    }

    public function destroy($id)
    {
        $value = Value::findOrFail($id);

        $value->delete();

        return response()->json($value);
    }
}
