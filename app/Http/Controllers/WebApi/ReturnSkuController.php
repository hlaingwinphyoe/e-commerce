<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\AppReturn;
use App\Models\Sku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnSkuController extends Controller
{
    public function store(Request $request, $id)
    {
        $return = AppReturn::find($id);

        $sku = DB::transaction(function () use ($request, $return) {
            $sku = $return->skus()->where('sku_id', $request->sku)->first();

            $old_qty = 0;

            if ($sku) {
                $old_qty = $sku->pivot->qty;
                $sku = $sku->pivot->update([
                    'qty' => $request->qty,
                    'price' => $request->price
                ]);
            } else {
                $sku  = $return->skus()->attach([$request->sku => [
                    'qty' => $request->qty,
                    'price' => $request->price,
                ]]);
            }

            $org_sku = Sku::find($request->sku);
            //update sku->stock
            $org_sku->update(['stock' => $org_sku->stock + $request->qty - $old_qty]);

            return $sku;
        });

        return response()->json([
            'skus' => $return->skus
        ]);
    }

    public function destroy($return, $sku)
    {
        $return = AppReturn::find($return);

        $sku = DB::transaction(function () use ($return, $sku) {            

            $sku_pivot = $return->skus()->where('sku_id', $sku)->first();

            $sku = Sku::find($sku);

            $sku->update(['stock' => $sku->stock - $sku_pivot->pivot->qty]);

            $return->skus()->detach($sku->id);

            return $sku;
        });

        return response()->json([
            'skus' => $return->skus
        ]);
    }
}
