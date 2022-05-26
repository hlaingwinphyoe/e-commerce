<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sku;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function show($id)
    {
        $sku = Sku::with(['item'])->findOrFail($id);

        return view('admin.barcodes.show')->with([
            'sku' => $sku
        ]);
    }
}
