<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Sku;
use Illuminate\Http\Request;

class SkuBarcodeController extends Controller
{
    public function update(Request $request, $id)
    {
        $sku = Sku::with(['item'])->find($id);

        $sku->update([
            'code' => $request->barcode ?? uniqid()
        ]);

        return response()->json($sku);
    }
}
