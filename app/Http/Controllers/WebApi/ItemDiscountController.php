<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class ItemDiscountController extends Controller
{
    public function store(Request $request)
    {
        $discount = Discount::updateOrCreate([
            'discountype_id' => $request->discountype_id
        ],[
            'amt' => $request->amt,
            'status_id' => $request->status_id,
            'discountype_id' => $request->discountype_id,
            'expired' => $request->end_date,
            'role_id' => $request->role_id
        ]);

        $discount = Discount::with(['role', 'status'])->find($discount->id);

        return response()->json($discount);
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::with(['role', 'status'])->findOrFail($id);

        $discount->update([
            'amt' => $request->amt,
            'status_id' => $request->status_id
        ]);

        return response()->json($discount);
    }

    public function destroy($id)
    {
        $discount = Discount::with(['role', 'status'])->findOrFail($id);

        $discount->delete();

        return response()->json($discount);
    }
}
