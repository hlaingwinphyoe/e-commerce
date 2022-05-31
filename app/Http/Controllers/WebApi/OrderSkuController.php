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
        $order = Order::find($id);

        $org_sku = Sku::findOrFail($request->sku);

        $sku_status = Status::where('slug', 'order-accepted')->first();

        $stock = $order->skus()->where('sku_id', $request->sku)->first();

        $qty = $request->qty ?? 1;

        if (!$stock && $org_sku->stock >= $qty) {
            $sku = Sku::find($request->sku);
            $name = $sku->item ? $sku->item->name : '';
            $name .= $sku->data ? '(' . $sku->data . ')' : '';
            $data = [
                'sku' => [
                    'name' => $name
                ]
            ];
            $order->skus()->create([
                'sku_id' => $sku ? $sku->id : '',
                'data' => json_encode($data),
                'qty' => $qty,
                'price' => $sku->getQtyPrice($qty),
                'customized_price' => $sku->getQtyPrice($qty),
                'status_id' => $sku_status ? $sku_status->id : 1,
                'buy_price' => $sku->buy_price,
            ]);

            $sku = Sku::find($request->sku);

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
        } else if ($stock && $org_sku->stock >= $qty) {
            $stock->update([
                'qty' => $stock->qty + $qty,
                'price' => $stock->sku->getQtyPrice($qty),
                'customized_price' => $stock->sku->getQtyPrice($qty),
            ]);
            $stock->sku->update(['stock' => $stock->sku->stock - $qty]);
        }
        dd('nothing');

        Order::find($id)->updateStockPrice();

        $stocks = $order->skus()->get();

        return response()->json($stocks);
    }

    public function update(Request $request, $order, $sku)
    {
        $order = Order::find($order);

        $stock = $order->skus()->where('sku_id', $sku)->first();

        DB::transaction(function () use ($stock, $request, $order) {

            $status = Status::where('slug', 'order-accepted')->first();

            if ($stock->status_id == $status->id && $stock->sku) {
                $st = $stock->sku->stock + $stock->qty - $request->qty;
                $stock->sku->update([
                    'stock' => $st
                ]);
            }

            $stock->update([
                'qty' => $request->qty,
                'price' => $stock->sku ? $stock->sku->getQtyPrice($request->qty) : $stock->price,
                'customized_price' => $stock->sku ? $stock->sku->getQtyPrice($request->qty) : $stock->price,
            ]);
        });

        $stocks = $order->skus()->get();

        $order->updateStockPrice();

        return response()->json($stocks);
    }

    public function destroy($order, $sku_id)
    {
        $order = Order::find($order);

        DB::transaction(function () use ($order, $sku_id) {
            $status = Status::where('slug', 'order-accepted')->first();

            $stock = $order->skus()->where('sku_id', $sku_id)->first();

            if ($stock->status_id == $status->id && $stock->sku) { //order accepted လုပ်ပြီးသားဆိုရင် stock ပြန်နှုတ်ပေးပါ
                $stock->sku->update(['stock' => $stock->sku->stock + $stock->qty]);
            }

            $stock->delete();

            $order->updateStockPrice();
        });

        $stocks = $order->skus()->with(['sku.item'])->get();

        return response()->json($stocks);
    }
}
