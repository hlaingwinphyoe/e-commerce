<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function store(Request $request)
    {
        $pricing = Pricing::create([
            'amt' => (float) $request->waste,
            'status_id' => $request->status_id,
            'min_qty' => 1,
            'max_qty' => 1,
            'role_id' => $request->role_id
        ]);

        $pricing = Pricing::with(['role', 'status'])->find($pricing->id);

        return response()->json($pricing);
    }

    public function update(Request $request, $id)
    {
        $pricing = Pricing::with(['role', 'status'])->findOrFail($id);

        $pricing->update([
            'amt' => (float) $request->waste,
            'status_id' => $request->status_id,
            'min_qty' => 1,
            'max_qty' => 1,
            'role_id' => $request->role_id

        ]);

        return response()->json($pricing);
    }

    public function destroy($id)
    {
        $pricing = Pricing::with(['role', 'status'])->findOrFail($id);

        $pricing->delete();

        return response()->json($pricing);
    }
}
