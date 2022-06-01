<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\Status;
use App\Models\Role;
use Carbon\Carbon;

class POSController extends Controller
{
    public function index()
    {
        $orders = Order::isType('pos')->filterOn()->orderBy('status_id')->orderBy('order_no', 'desc')->paginate(20);

        $deliveries = Delivery::all();

        $statuses = Status::isType('order')->get();

        $price_statuses = Status::isType('price')->get();

        $roles = Role::isType('Customer')->orderBy('name')->get();

        return view('admin.pos.index')->with([
            'orders' => $orders,
            'statuses' => $statuses,
            'deliveries' => $deliveries,
            'price_statuses' => $price_statuses,
            'roles' => $roles,
        ]);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);

        $paymentypes = Status::isType('payment-type')->get();

        return view('admin.pos.show')->with([
            'order' => $order,
            'paymentypes' => $paymentypes
        ]);
    }

    public function create()
    {
        $order = request('order_no') ? Order::where('order_no', request('order_no'))->orWhere('id', request('order_no'))->first() : Order::isType('pos')->where('user_id', auth()->user()->id)->whereDoesntHave('status', function ($q) {
            $q->where('slug', 'completed');
        })->whereDoesntHave('skus')->first();

        if (!$order) {
            $data = [
                'user' => [
                    'name' => '',
                    'email' => '',
                    'phone' => '',
                    'address' => '',
                    'remark' => '',
                    'region' => [
                        'region_id' => '',
                        'township_id' => ''
                    ],
                    'delifee' => 0
                ],
            ];
            $order_month = Carbon::now()->format('ym');
            
            $latest_order = Order::where('order_month', intval($order_month))->orderBy('order_no', 'desc')->first();

            $order_no = $latest_order ? $latest_order->return_no + 1 : intval($order_month . '00001');
            $order = Order::create([
                'order_no' => $order_no,
                'order_month' => $order_month,
                'user_id' => auth()->user()->id,
                'status_id' => 1,
                'type' => 'pos',
                'data' => json_encode($data)
            ]);
        }

        $skus = $order->skus()->get();

        $statuses = Status::isType('price')->orderBy('priority')->get();

        $order = Order::with(['transactions.status'])->find($order->id);

        return view('admin.pos.create')->with([
            'order' => $order,
            'skus' => $skus,
            'statuses' => $statuses,
        ]);
    }

    public function print($id)
    {
        $sale = Order::findOrFail($id);

        return view('admin.print.order')->with([
            'sale' => $sale
        ]);
    }
}
