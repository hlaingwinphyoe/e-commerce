<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\AppReturn;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function update(Request $request, $id)
    {
        $return = AppReturn::findOrFail($id);

        $return->update([
            'date' => $request->date,
            'order_id' => $request->order_id
        ]);

        return response()->json($return);
    }
}
