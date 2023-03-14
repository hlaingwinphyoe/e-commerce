<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class GeneralInventoryController extends Controller
{
    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $inventory->update(['remark' => $request->remark]);

        return response()->json($inventory);
    }

    public function publish(Request $request, $id)
    {
        $inventory = Inventory::with('skus')->find($id);

        $inventory->update([
            'is_published' => 1,
        ]);

        // $inventory->stockUpdate($inventory->type == 'general' ? 'remove' : 'add', $inventory->is_published);
        $inventory->stockUpdate('remove', $inventory->is_published);

        return response()->json($inventory);
    }

    public function getPatient($id)
    {
        $inventory = Inventory::findOrFail($id);

        $patient = $inventory->return_form->patient;

        return response()->json([
            'id' => $patient ? $patient->id : '',
            'name' => $patient ? $patient->name : '',
            'phone' => $patient ? $patient->phone: '',
        ]);
    }
}
