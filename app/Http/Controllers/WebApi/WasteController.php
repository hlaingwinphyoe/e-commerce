<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Waste;
use Illuminate\Http\Request;

class WasteController extends Controller
{
    public function store(Request $request)
    {
        $waste = Waste::with('status')->create([
            'amt' => $request->amt,
            'status_id' => $request->status_id,
            'type' => 'waste'
        ]);

        $waste = Waste::with('status')->find($waste->id);

        return response()->json($waste);
    }

    public function update(Request $request, $id)
    {
        $waste = Waste::with('status')->findOrFail($id);

        $waste->update([
            'amt' => $request->amt,
            'status_id' => $request->status_id,
            'type' => 'waste'
        ]);

        return response()->json($waste);
    }

    public function destroy($id)
    {
        $waste = Waste::with('status')->findOrFail($id);

        $waste->delete();

        return response()->json($waste);
    }
}
