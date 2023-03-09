<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $date = request('date') ?? now();

        $expenses = Expense::whereDate('date', $date)->orderBy('name')->get();

        return response()->json(ExpenseResource::collection($expenses));
    }


    public function store(Request $request)
    {
        $expense = DB::transaction(function () use($request){

            $expense = Expense::firstOrCreate([
                'name' => $request->name,
                'date' => $request->date,
            ],[
                'user_id' => auth()->user()->id,
                'type_id' => $request->type_id,
                'name' => $request->name,
                'amount' => $request->amount,
                'date' => $request->date ?? now(),
                'reference_id' => $request->reference_id,
                'supplier_id' => $request->supplier_id,
            ]);

            return $expense;
        });

        $expenses = Expense::whereDate('date', $expense->date)->orderBy('name')->get();

        return response()->json(ExpenseResource::collection($expenses));

    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $expense->update(['amount' => $request->amount]);

        $expenses = Expense::whereDate('date', $expense->date)->orderBy('name')->get();

        return response()->json(ExpenseResource::collection($expenses));
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);

        $date = $expense->date;

        $expense->delete();

        $expenses = Expense::whereDate('date', $date)->orderBy('name')->get();

        return response()->json(ExpenseResource::collection($expenses));

    }
}
