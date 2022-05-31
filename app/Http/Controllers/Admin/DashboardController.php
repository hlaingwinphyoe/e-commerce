<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Currency;
use App\Models\Order;
use App\Models\DiscountType;
use App\Models\Group;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $currencies = Currency::whereNotIn('slug', ['mmk'])->get();
        $exchange_rates = $currencies->map(function ($currency) {
                    return $currency->exchangerates()->with('currency')->latest()->first();
                });
        
        $discounts = DiscountType::availableIn()->get();
        $orders = Order::isType('order')->pendingOrder()->take(5)->get();

        return view('admin.dashboard.index',compact('exchange_rates','orders','discounts'));
    }
}
