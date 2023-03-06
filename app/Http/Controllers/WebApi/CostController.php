<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Cost;
use Illuminate\Http\Request;

class CostController extends Controller
{
    public function store(Request $request)
    {
        $cost = Cost::create([
            'amt' => $request->amt,
            'currency_id' => $request->currency_id,
            'type' => 'cost'
        ]);

        return response()->json($cost);
    }

    public function update(Request $request, $id)
    {
        $cost = Cost::findOrFail($id);

        $cost->update([
            'amt' => $request->amt,
            'currency_id' => $request->currency_id,
            'type' => 'cost'
        ]);

        return response()->json($cost);
    }

    public function destroy($id)
    {
        $cost = Cost::findOrFail($id);

        $cost->delete();

        return response()->json($cost);
    }
}
