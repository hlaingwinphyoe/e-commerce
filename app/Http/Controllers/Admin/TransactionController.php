<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Status;

class TransactionController extends Controller
{
    public function index()
    {
        $from_date = request()->from_date ? Carbon::parse(request()->from_date) : now()->startOfMonth();
        $to_date = request()->to_date ? Carbon::parse(request()->to_date) : now()->endOfMonth();

        $trans = Transaction::filterOn()->whereDate('date', '>=', $from_date)->whereDate('date', '<=', $to_date)->latest()->paginate(20);

        $payments = Status::isType('payment-type')->get();

        return view('admin.transactions.index', [
            'trans' => $trans,
            'payments' => $payments
        ]);
    }

    public function nextPayment()
    {
        $from_date = request()->from_date ? Carbon::parse(request()->from_date) : now()->startOfMonth();
        $to_date = request()->to_date ? Carbon::parse(request()->to_date) : now()->endOfMonth();

        $trans = Transaction::where('next_date', '!=', null)->whereDate('date', '>=', $from_date)->whereDate('date', '<=', $to_date)->latest()->paginate(20);

        // return response()->json($trans);
        // if (request('from_date') || request('to_date')) {
        //     $trans = Transaction::where('next_date', '!=', null)->filterOn()->latest()->paginate(20);
        // } else {
        //     $trans = Transaction::where('next_date', '!=', null)->whereDate('next_date', now())->filterOn()->paginate(20);
        // }

        $payments = Status::isType('payment-type')->get();

        return view('admin.transactions.next-payment', [
            'trans' => $trans,
            'payments' => $payments
        ]);
    }
}
