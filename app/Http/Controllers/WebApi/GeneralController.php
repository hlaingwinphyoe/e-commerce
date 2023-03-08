<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Inventory;
use App\Models\Sku;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function store(Request $request, $inven)
    {
        $inventory = Inventory::find($inven);


        $sku = $inventory->skus()->where('sku_id', $request->sku)->first();

        $skuId = Sku::find($request->sku);

        $currency = Currency::where('id',$skuId->currency_id)->first();

        $rate = $currency->exchangerates()->latest()->first();


        if ($sku) {
            $sku->pivot->update([
                'qty' => $sku->pivot->qty + $request->qty,
                'amount' => $request->amount ?? $sku->pivot->amount,
                'rate' => $rate ? $rate->mmk : 1,
                'remark' => $request->remark ?? $sku->pivot->remark,
                'currency_id' => $currency ? $currency->id : 1
            ]);
        } else {
            $inventory->skus()->attach([$request->sku => [
                'qty' => $request->qty,
                'amount' => $request->amount,
                'rate' => $rate ? $rate->mmk : 1,
                'remark' => $request->remark,
                'currency_id' => $currency ? $currency->id : 1
            ]]);
        }

        return response()->json($inventory->skus);
    }

}
