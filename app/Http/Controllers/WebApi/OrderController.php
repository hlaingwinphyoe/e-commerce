<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

use App\Models\Status;
use App\Models\Sku;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::filterOn()->get();

        return response()->json($orders);
    }

    public function getOrders(Request $request)
    {
        if (request('latest')) {
            $orders = Order::whereNotNull('order_no')->latest()->take(5)->get();
        } else {
            $orders = Order::where('order_no', 'like', '%' . $request->order_no . '%')->orderBy('order_no')->get();
        }

        return response()->json($orders);
    }

    public function getBalance($id)
    {
        $order = Order::find($id);

        return $order ? $order->getBalance() : 0;
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        $status = Status::where('slug', $request->status)->first();

        $old_status = $order->status->slug;

        if (!$order->order_no) {
            $order_month = Carbon::now()->format('ym');

            $latest_order = Order::where('order_month', intval($order_month))->orderBy('order_no', 'desc')->first();

            $order_no = $latest_order ? $latest_order->order_no + 1 : intval($order_month . '00001');
        }

        if (!$order->price) {
            $order->updateStockPrice();
        }

        $order->update([
            'status_id' => $status->id,
            'order_no' => $order->order_no ?? $order_no,
            'order_month' => $order->order_month ?? $order_month,
            'remark' => $request->remark
        ]);

        $order = Order::find($order->id);

        if (($order->status->slug == 'completed' || $order->status->slug == 'order-confirmed') && $old_status != $request->status) {
            if ($order->type != 'pos') {
                $order->updateStockStatus('order-accepted');
                if ($old_status !== 'order-confirmed') {
                    $order->updateOrderedStock();
                }
            }

            if ($order->buyer) {
                $in_status = Status::where('slug', 'in')->first();
                $order->buyer->userpoints()->create([
                    'points' => $order->points,
                    'status_id' => $in_status->id
                ]);
            }
        }

        if ($order->status->slug == 'cancel' && $old_status != $request->status) {
            if ($old_status == 'order-confirmed' || $old_status == 'completed') {
                $order->updateStockStatus('sku-cancelled');
            }
        }

        return response()->json($order);
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::find($id);

        $order = DB::transaction(function () use ($order, $request) {
            foreach ($order->stockOrders as $stock) {
                if ($stock->sku) {
                    $stock->sku->update([
                        'stock' => $stock->sku->stock + $stock->qty
                    ]);
                }
                $stock->delete();
            }

            $order->updateStockPrice();

            $order->update(['data' => '']);

            return $order;
        });

        return response()->json($order);
    }

    public function saveCustomer(Request $request, $id)
    {
        $order = Order::find($id);

        $data = json_decode($order->data);

        $data->user->name = $request->name;
        $data->user->email = $request->email;
        $data->user->phone = $request->phone;
        $data->user->address = $request->address;
        $data->user->remark = $request->remark;


        $order_month = Carbon::now()->format('ym');

        $latest_order = Order::where('order_month', intval($order_month))->orderBy('order_no', 'desc')->first();

        $order_no = $latest_order ? $latest_order->order_no + 1 : intval($order_month . '00001');

        $order->update([
            'data' => json_encode($data),
            'order_month' => $order->order_month ? $order->order_month : $order_month,
            'order_no' => $order->order_no ?? $order_no,
            'customer_id' => $request->customer_id ?? $order->customer_id,
        ]);

        return response()->json($order);
    }

    public function addDiscount(Request $request, $id)
    {
        $order = Order::with('transactions.status')->find($id);

        $order->update([
            'discount_amt' => $request->amt,
            'discount_status' => $request->status_id,
            'remark' => $request->remark,
        ]);

        $order = Order::with('transactions.status')->find($id);

        return response()->json($order);
    }

    public function addDeliFee(Request $request, $id)
    {
        $order = Order::with('transactions.status')->find($id);

        $data = json_decode($order->data);

        $data->user->delifee = $request->deli_fee;

        $order->update(['data' => json_encode($data)]);

        $order = Order::with('transactions.status')->find($id);

        return response()->json($order);
    }
}
