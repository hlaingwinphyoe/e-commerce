<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Sku;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function monthly()
    {
        return view('admin.reports.monthly');
    }

    public function summary()
    {
        // inventory-values
        $skus = Sku::where('stock', '>', 0)->get();

        $stock_values = $skus->reduce(function ($total, $sku) {
            return $total + ($sku->stock * $sku->buy_price);
        }, 0);

        $start_date = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::now()->startOfMonth();

        $end_date = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::now()->endOfMonth();

        $sales = Order::saleOrder()->filterOn()->fromTo()->get();

        $sales_data = [
            'total' => 0,
        ];

        foreach ($sales as $sale) {
            $sales_data['total'] += $sale->getSubTotal();
        }

        $purchases = Inventory::fromTo()->where('is_published', 1)->get();

        $purchase_data = [
            'total' => 0,
            // 'pay' => 0,
            // 'balance'  => 0
        ];

        foreach ($purchases as $purchase) {
            $purchase_data['total'] += $purchase->getAmount();
            // $purchase_data['pay'] += $purchase->getPayAmount();
            // $purchase_data['balance'] += $purchase->getBalance();
        }

        $expenses = Expense::fromTo()->get();

        $expense_total = $expenses->sum(function ($expense) {
            return $expense->amount;
        });

        return view('admin.reports.summary')->with([
            'stock_values' => $stock_values,
            'sales_data' => $sales_data,
            'purchase_data' => $purchase_data,
            'expense_total' => $expense_total,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }
}
