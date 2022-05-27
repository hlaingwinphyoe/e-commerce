<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Status;
use Excel;
use App\Exports\SalesExport;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Order::saleOrder()->filterOn()->fromTo()->orderBy('order_no', 'desc');

        $sale_data = [
            'total' => 0,
            'received' => 0,
            'net_total' => 0,
            'balance' => 0,
            'deli_fee' => 0
        ];

        foreach($sales->get() as $sale) {
            $total = $sale->return() ? $sale->getSubTotal() - $sale->return()->price : $sale->getSubTotal();
            $sale_data['total'] += $total; // စုပေါင်း
            $sale_data['net_total'] += $sale->return() ? $sale->getSubTotal() - $sale->return()->price : $sale->getSubTotal(); // ကျသင့်ငွေ
            $sale_data['deli_fee'] += $sale->deli_fee;
            $sale_data['received'] += $sale->getPayAmount() - $sale->getReturnAmount() - $sale->getChange();
            $sale_data['balance'] += $sale->getPayAmount() - $sale->getReturnAmount() - $sale->getChange()  == $total && abs($sale->getBalance()) == 0 ? 0 : $sale->getBalance() + $sale->getChange(); 
            // $sale_data['balance'] += $sale->getBalance() + $sale->getReturnAmount() != $sale->price  && $sale->getBalance() > 0 ? $sale->getBalance() : 0;
        }

        $sales = $sales->paginate(20);

        $statuses = Status::isType('order')->get();

        $price_statuses = Status::isType('price')->get();

        return view('admin.sales.index')->with([
            'sales' => $sales,
            'statuses' => $statuses,
            'sale_data' => $sale_data,
            'price_statuses' => $price_statuses,
        ]);
    }

    public function show($id)
    {
        $sale = Order::saleOrder()->findOrFail($id);

        return view('admin.sales.show')->with([
            'sale' => $sale
        ]);
    }

    public function print($id) 
    {
        // $sale = Order::saleOrder()->findOrFail($id);
        $sale = Order::findOrFail($id);
        
        return view('admin.print.sales')->with([
            'sale' => $sale
        ]);
    }

    public function excel()
    {
        $date = \Carbon\Carbon::now()->format('d-m-Y');
        return Excel::download(new SalesExport, 'sales-[' . $date . '].xlsx');
    }
}
