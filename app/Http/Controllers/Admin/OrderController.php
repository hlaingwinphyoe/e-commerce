<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Delivery;
use App\Models\Item;
use App\Models\Order;
use App\Models\Status;
use App\Models\Township;
use App\Models\Region;
use App\Models\User;
use App\Notifications\OrderStatus;

use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-order')->only(['index', 'show']);
        $this->middleware('permissions:create-order')->only(['create', 'store']);
        $this->middleware('permissions:edit-order')->only(['edit', 'update']);
        $this->middleware('permissions:delete-order')->only('destroy');
    }

    public function index()
    {
        $orders = Order::isType('order')->filterOn()->orderBy('status_id')->orderBy('order_no')->paginate(20);

        $statuses = Status::isType('order')->get();

        $deliveries = Delivery::orderBy('name')->get();

        $price_statuses = Status::isType('price')->get();

        return view('admin.orders.index')->with([
            'orders' => $orders,
            'statuses' => $statuses,
            'deliveries' => $deliveries,
            'price_statuses' => $price_statuses
        ]);
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);

        $statuses = Status::isType('order')->get();

        $sku_statuses = Status::isType('sku-status')->get();

        $items = Item::get();

        $price_statuses = Status::isType('price')->get();

        $townships = Township::filterOn()->orderBy('name')->get();

        $regions = Region::filterOn()->orderBy('name')->get();

        return view('admin.orders.edit')->with([
            'order' => $order,
            'statuses' => $statuses,
            'sku_statuses' => $sku_statuses,
            'items' => $items,
            'price_statuses' => $price_statuses,
            'townships' => $townships,
            'regions' => $regions
        ]);
    }

    // new DB Structure stockOrders
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $old_status = $order->status_id;

        $old_slug = $order->status->slug;

        $request->validate([
            'status_id' => 'required'
        ]);

        $order->update([
            'status_id' => $request->status_id,
            'user_id' => auth()->user()->id,
            'remark' => $request->remark
        ]);

        $order = Order::find($order->id);

        if ($order->status->slug == 'completed' && $old_status != $request->status_id && $order->buyer) {
            $in_status = Status::where('slug', 'in')->first();
            $order->buyer->userpoints()->create([
                'points' => $order->points,
                'status_id' => $in_status->id
            ]);
        }

        if ($order->hasPreOrderedSku() && $order->status->slug == 'order-confirmed') {

            $order_month = Carbon::now()->format('ym');

            $latest_order = Order::where('order_month', intval($order_month))->orderBy('order_no', 'desc')->first();

            $order_no = $latest_order ? $latest_order->order_no + 1 : intval($order_month . '00001');

            $status = Status::where('slug', 'pending')->first();

            $new_order = Order::create([
                'order_no' => $order_no,
                'order_month' => $order_month,
                'customer_id' => $order->customer_id,
                'status_id' => $status->id,
                'data' => $order->data,
                'type' => 'pre-order'
            ]);

            foreach ($order->getPreorderedStocks() as $sku) {
                $sku->update([
                    'order_id' => $new_order->id,
                ]);
            }

            $new_order->updateStockPrice();

            Order::find($id)->updateStockPrice();
        }

        if ($order->status->slug == 'cancel' && $old_status != $request->status_id) {
            $order->updateStockStatus('sku-cancelled');
        }

        return redirect($request->session()->get('prev_route'))->with('message', 'Order updated successfully!');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        DB::transaction(function () use ($order) {
            if ($order->type == 'pos') {
                $order->updateStockStatus('sku-cancelled');
                // $order->updateSkuStatus('sku-cancelled');
            }
            $order->delete();
        });

        return redirect(request()->session()->get('prev_route'))->with('message', 'Order deleted successfully!');
    }


    public function removeSku($order, $sku)
    {
        $order = Order::findOrFail($order);

        $stock = $order->stockOrders()->where('id', $sku)->first();

        if ($stock) {
            $stock->delete();
            $order->updateStockPrice();
        }

        return redirect()->back();
    }

    public function updateSkuQty(Request $request, $order, $sku)
    {
        $order = Order::findOrFail($order);

        $stock = $order->stockOrders()->where('id', $sku)->first();

        if ($stock) {
            $stock->update([
                'qty' => $request->qty,
            ]);
            $order->updateStockPrice();
        }

        return redirect()->back();
    }

    public function updateSkuStatus(Request $request, $order, $sku)
    {
        $order = Order::findOrFail($order);

        $stock = $order->stockOrders()->where('id', $sku)->first();

        if ($stock) {
            $stock->update([
                'status_id' => $request->status_id,
            ]);
            $order->updateStockPrice();
        }

        return redirect()->back();
    }

    public function addItem(Request $request, Order $order)
    {
        $sku = Sku::find($request->sku);
        $name = $sku->item ? $sku->item->name : '';
        $name .= $sku->data ? '(' . $sku->data . ')' : '';
        $data = [
            'sku' => [
                'name' => $name
            ]
        ];

        if ($sku) {
            $status = Status::where('slug', 'ordered')->first();
            $order->stockOrders()->create([
                'sku_id' => $sku ? $sku->id : '',
                'data' => json_encode($data),
                'qty' => 1,
                'price' => $sku->price,
                'customized_price' => $sku->price,
                'status_id' => $status->id
            ]);
            $order->updateStockPrice();
        }

        return redirect()->back();
    }

    public function restore($id)
    {
        $order = Order::withTrashed()->findOrFail($id);

        $order->restore();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Order was successfully restored');
    }

    public function delete($id)
    {
        $order = Order::withTrashed()->findOrFail($id);

        $order->forceDelete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Order was permently deleted');
    }

    public function updateDelivery(Request $request, $id)
    {

        $order = Order::findOrFail($id);

        $order->deliveries()->sync([$request->delivery => ['date' => Carbon::now()]]);

        return redirect($request->session()->get('prev_route'))->with('message', 'Order Delivery Updated');
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'status_id' => $request->status,
        ]);

        return redirect($request->session()->get('prev_route'))->with('message', 'Order Status Updated');
    }

    public function updateInfo(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = json_decode($order->data);

        $data->user->region->township_id = $request->township_id;
        $data->user->region->region_id = $request->region_id;
        $data->user->address = $request->address;

        $order->update(['data' => json_encode($data)]);

        return redirect()->back()->with('message', 'Update Order Info');
    }

    public function return(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        DB::transaction(function () use ($request, $order) {
            $cancel_status = Status::where('slug', 'cancel')->first();

            $out_status = Status::where('slug', 'out')->first();

            if ($request->remark) {
                $text = 'Remark: ' . $request->remark;
                $order->update(['remark' => $order->remark . $text]);
            }
            if ($order->status->slug == 'completed' || $order->status->slug == 'order-confirmed') {
                // $order->updateSkuStatus('sku-cancelled');
                $order->updateStockStatus('sku-cancelled');
            }
            if ($request->paymentype_id) {
                $order->transactions()->create([
                    'amount' => $order->getPayAmount(),
                    'paymentype_id' => $request->paymentype_id,
                    'user_id' => auth()->user()->id,
                    'status_id' => $out_status->id,
                    'date' => now(),
                    'remark' => 'For Return'
                ]);
            }
            $order->update(['status_id' => $cancel_status->id]);

            return rredirect()->back()->with('message', 'Order was successfully returned.');
        });
    }
}
