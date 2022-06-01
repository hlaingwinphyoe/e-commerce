<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Currency;
use App\Models\Order;
use App\Models\DiscountType;
use App\Models\Group;
use App\Models\Status;
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

    public function changeHotline(Request $request, $id)
    {
        $group = Status::findOrFail($id);

        $group->update([
            'name' => $request->phone ?? ' ',
        ]);

        return redirect($request->session()->get('prev_route'));
    }

    public function changeGeneral(Request $request)
    {
        $group = Status::isType('general')->first();

        $group->update([            
            'name' => $request->general ?? ' ',
        ]);

        return redirect($request->session()->get('prev_route'));
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);

        if(File::exists(public_path('images/logo.png'))) {
            //delete old file
            File::delete(public_path('images/logo.png'));
        }

        $file = $request->file('image');
        $fileNameToStore = 'logo.png';
        $url = $file->storeAs('public/images', $fileNameToStore);

        return redirect()->back();

    }
}
