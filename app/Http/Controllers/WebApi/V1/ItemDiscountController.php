<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\DiscountType;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemDiscountController extends Controller
{
    public function index($id)
    {
        $item = Item::findOrFail($id);

        return response()->json($item->discounts()->with('discountype')->first());
    }

    public function store(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $discountype = DiscountType::firstOrCreate([
            'name' => $request->name,
        ], [
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'status_id' => $request->status,
            'amt' => $request->amt,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        if ($discountype) {
            $discount = $item->discounts()->create([
                'amt' => $discountype->amt,
                'status_id' => $discountype->status_id,
                'discountype_id' => $discountype->id,
                'expired' => $discountype->end_date,
                'role_id' => 5
            ]);
            $discount = Discount::with(['role', 'status', 'discountype'])->find($discount->id);

            return response()->json([
                'discount' => $discount,
                'amount' => $item->discount,
            ]);
        }

        return response()->json([]);
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
