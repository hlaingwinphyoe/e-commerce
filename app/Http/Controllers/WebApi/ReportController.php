<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sales()
    {
        $start_date = request('month') ? Carbon::parse(request('month'))->startOfMonth() : Carbon::now()->startOfMonth();

        $end_date = request('month') ? Carbon::parse(request('month'))->endOfMonth() : Carbon::now()->endOfMonth();

        $min_date = Order::saleOrder()->min('updated_at');

        $max_date = Order::saleOrder()->max('updated_at');

        $period = CarbonPeriod::create($start_date, $end_date);

        $sales_date = [];

        $sales_data = [];

        $sales_total = 0;

        $cates = [];

        foreach ($period as $date) {
            if (Carbon::parse($date)->format('Y-m-d') >= Carbon::parse($min_date)->format('Y-m-d') && Carbon::parse($date)->format('Y-m-d') <= Carbon::parse($max_date)->format('Y-m-d')) {
                array_push($sales_date, $date->format('d M'));
                $orders = Order::saleOrder()->whereDate('updated_at', $date)->get();
                $total = $orders->reduce(function ($total, $order) {
                    return $total + $order->price;
                }, 0);
                array_push($sales_data, $total);
                $sales_total += $total;


                foreach($orders as $order){
                    foreach($order->skus as $sku) {
                        if($type = $sku->item->type()) {
                            if(array_key_exists($type->name, $cates)) {
                                $cates[$type->name]['total'] += $sku->pivot->price * $sku->pivot->qty;
                            }else {
                                $cates[$type->name] = [
                                    'name' => $type->name,
                                    'total' => $sku->pivot->price * $sku->pivot->qty,
                                ];
                            }

                            if($sku) {
                                // dd($cates);
                            }
                        }                            
                    }
                }
            }
        }

        $cates_label = [];
        $cates_data = [];

        foreach($cates as $cate) {
            array_push($cates_label, $cate['name']);
            array_push($cates_data, $cate['total']);
        }

        $data = [
            'sales' => [
                'labels' => $sales_date,
                'datasets' => $sales_data,
                'total' => $sales_total,
            ],
            'category' => [
                'labels' => $cates_label,
                'datasets' => $cates_data,
            ]
        ];

        return response()->json($data);
    }

    public function getData()
    {
        $start_date = request('month') ? Carbon::parse(request('month'))->startOfMonth() : Carbon::now()->startOfMonth();

        $end_date = request('month') ? Carbon::parse(request('month'))->endOfMonth() : Carbon::now()->endOfMonth();

        $sales = Order::saleOrder()->filterOn()->whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date)->get();

        $sale_data = [
            'total' => 0,
            'received' => 0,
            'net_total' => 0,
            'balance' => 0,
            'deli_fee' => 0
        ];

        foreach ($sales as $sale) {
            $total = $sale->return() ? $sale->getSubTotal() - $sale->return()->price : $sale->getSubTotal();
            $sale_data['total'] += $total; // စုပေါင်း
            $sale_data['net_total'] += $sale->return() ? $sale->getSubTotal() - $sale->return()->price : $sale->getSubTotal(); // ကျသင့်ငွေ
            $sale_data['deli_fee'] += $sale->deli_fee;
            $sale_data['received'] += $sale->getPayAmount() - $sale->getReturnAmount() - $sale->getChange();
            $sale_data['balance'] += $sale->getPayAmount() - $sale->getReturnAmount() - $sale->getChange()  == $total && abs($sale->getBalance()) == 0 ? 0 : $sale->getBalance() + $sale->getChange();
        }

        return response()->json($sale_data);
    }

    public function monthPeriod()
    {
        $ary = [];

        $min_date = Order::min('created_at');

        // $max_date = Order::max('created_at');

        $period = CarbonPeriod::create($min_date, now(), '1 Month');

        foreach($period as $month) {
            array_push($ary, $month->format('M Y'));
        }

        return response()->json([
            'months' => $ary,
            'current' => now()->format('M Y') 
        ]);
    }
}
