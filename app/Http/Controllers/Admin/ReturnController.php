<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppReturn;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = AppReturn::latest()->orderBy('return_no')->paginate(20);

        return view('admin.returns.index')->with([
            'returns' => $returns
        ]);
    }

    public function create()
    {
        return view('admin.returns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'date' => 'required'
        ]);
        
        $return_month = Carbon::now()->format('ym');

        $latest_return = AppReturn::where('return_month', intval($return_month))->orderBy('return_no', 'desc')->first();

        $return_no = $latest_return ? $latest_return->return_no + 1 : intval($return_month . '00001');

        $return = AppReturn::create([
            'return_no' => $return_no,
            'return_month' => $return_month,
            'user_id' => auth()->user()->id,
            'date' => $request->date ?? now(),
            'order_id' => $request->order_id ?? '',
        ]);

        return redirect()->route('admin.returns.edit', $return->id);
    }

    public function edit($id)
    {
        $return = AppReturn::findOrFail($id);

        $orders = Order::latest()->get();

        return view('admin.returns.edit')->with([
            'return' => $return,
            'orders' => $orders,
        ]);
    }

    public function show($id)
    {
        $return = AppReturn::findOrFail($id);

        return view('admin.returns.show')->with([
            'return' => $return
        ]);
    }

    public function destroy($id)
    {
        $return = AppReturn::findOrFail($id);

        $return->delete();

       return redirect()->back()->with('message', 'Destroyed Successfully.');
    }
}
