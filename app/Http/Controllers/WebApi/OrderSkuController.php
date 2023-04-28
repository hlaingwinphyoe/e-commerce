<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Sku;
use App\Models\Status;
use Carbon\Carbon;

class OrderSkuController extends Controller
{
    public function store(Request $request, $id)
    {
        // dd($request->all());
        $order = Order::find($id);

        $org_sku = Sku::findOrFail($request->sku);

        $sku_status = Status::where('slug', 'order-accepted')->first();

        $sku = $order->skus()->where('sku_id', $request->sku)->with(['item'])->first();

        $qty = $request->qty ?? 1;

        if (!$sku && $org_sku->stock >= $qty) {
            $sku = Sku::find($request->sku);
            $sku = $order->skus()->attach([$request->sku => [
                'status_id' => $sku_status ? $sku_status->id : 1,
                'qty' => $qty,
                // 'price' => $sku->price,
                'price' => $request->price,
                'customized_price' => $request->price,
                'margin' => 0
            ]]);

            $sku = $order->skus()->where('sku_id', $request->sku)->with(['item'])->first();

            $sku->update(['stock' => $sku->stock - $qty]);

            if (!$order->order_no) {
                $order_month = Carbon::now()->format('ym');

                $latest_order = Order::where('order_month', intval($order_month))->orderBy('order_no', 'desc')->first();

                $order_no = $latest_order ? $latest_order->order_no + 1 : intval($order_month . '00001');

                $order->update([
                    'order_no' => $order->order_no ?? $order_no,
                    'order_month' => $order->order_month ?? $order_month,
                ]);
            }
        } else if ($sku && $org_sku->stock >= $qty) {
            $sku->pivot->update([
                'qty' => $sku->pivot->qty + $qty,
                // 'price' => $sku->price,
                // 'customized_price' => $sku->price,
                'price' => $request->price,
                'customized_price' => $request->price,
            ]);
            $sku->update(['stock' => $sku->stock - $qty]);
        } else {
            //no stock enough
        }

        Order::find($id)->updatePrice();

        // $org_sku->update(['stock' => $org_sku->stock - $request->qty]);

        $skus = $order->skus()->with(['item'])->get();

        return response()->json($skus);
    }

    public function update(Request $request, $order, $sku)
    {
        $order = Order::find($order);

        $sku = $order->skus()->where('sku_id', $sku)->first();
        
        DB::transaction(function () use ($sku, $request, $order) {

            $status = Status::where('slug', 'order-accepted')->first();

            if ($sku->pivot->status_id == $status->id) {
                $stock = $sku->stock + $sku->pivot->qty - $request->qty;
                $sku->update([
                    'stock' => $stock
                ]);
            }

            $sku->pivot->update([
                'qty' => $request->qty,
                'price' => $sku->pivot->price,
                'customized_price' => $sku->pivot->price,
                // 'price' => $request->price,
                // 'customized_price' => $request->price,
            ]);
        });

        $skus = $order->skus()->with(['item'])->get();

        $order->updatePrice();

        return response()->json($skus);
    }

    public function destroy($order, $sku_id)
    {
        $order = Order::find($order);

        DB::transaction(function () use ($order, $sku_id) {
            $status = Status::where('slug', 'order-accepted')->first();

            $sku = $order->skus()->where('sku_id', $sku_id)->first();

            if ($sku->pivot->status_id == $status->id) { //order accepted လုပ်ပြီးသားဆိုရင် stock ပြန်နှုတ်ပေးပါ
                $sku->update(['stock' => $sku->stock + $sku->pivot->qty]);
            }

            $order->skus()->detach($sku_id);

            $order->updatePrice();
        });

        $skus = $order->skus()->with(['item'])->get();

        return response()->json($skus);
    }


}
