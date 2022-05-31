<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $order = Order::findOrFail(request()->order_id);

        $transactions = $order->transactions()->with(['status'])->get();

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $transaction = DB::transaction(function () use ($order, $request) {

            $status = Status::where('slug', 'in')->first();

            $out_status = Status::where('slug', 'out')->first();

            if (abs($request->amount) > 0) {
                $transaction = $order->transactions()->create([
                    'amount' => abs($request->amount),
                    'status_id' => $order->getBalance() > 0 ? $status->id : $out_status->id,
                    'paymentype_id' => $request->paymentype_id,
                    'user_id' => auth()->user()->id,
                    'date' => $request->date ?? now(),
                    'next_date' => $request->next_date,
                    'remark' => $request->remark
                ]);
            }
            $order = Order::find($request->order_id);

            if ($order->getBalance() == 0 || $order->getBalance() == 0.00) {
                $order->updateStatus('completed');
            }

            $order->update([
                'debt' => $order->getBalance()
            ]);
        });

        $order = Order::with(['transactions.status'])->find($request->order_id);

        // $transaction = Transaction::where('id', $transaction->id)->with(['status'])->first();

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->updte([
            'paymentype_id' => $request->paymentype_id,
            'amount' => $request->amount
        ]);

        return response()->json($transaction);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->delete();

        return response()->json($transaction);
    }
}
