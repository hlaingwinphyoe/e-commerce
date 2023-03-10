<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Supplier;
use App\Models\Type;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::filterOn()->latest()->orderBy('name')->paginate(20);

        $types = Type::where('type', 'expense')->orderBy('name')->get();

        return view('admin.expenses.index')->with([
            'expenses' => $expenses,
            'types' => $types,
        ]);
    }

    public function create()
    {
        $types = Type::where('type', 'expense')->orderBy('name')->get();

        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.expenses.create')->with([
            'types' => $types,
            'suppliers' => $suppliers
        ]);
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);

        $types = Type::where('type', 'expense')->orderBy('name')->get();

        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.expenses.edit')->with([
            'expense' => $expense,
            'types' => $types,
            'suppliers' => $suppliers,
        ]);
    }

    public function update(Request $request,$id)
    {
        $expense = Expense::findOrFail($id);

        $expense->update([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'amount' => $request->amount,
            'type_id' => $request->type_id,
            'reference_id' => $request->reference_id,
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('admin.expenses.index')->with('message', 'Updated Successfully');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);

        // $date = $expense->date;

        $expense->delete();

        return redirect()->back()->with('message', 'Deleted Successfully');

    }

    public function total()
    {
        $earliest = Expense::orderBy('created_at')->first();

        if($earliest){
            $min_month = Carbon::parse($earliest->created_at);

            $period = CarbonPeriod::create($min_month, '1 month', now()->addMonth())->toArray();

            $start = request('month') ? Carbon::parse(request('month'))->startOfMonth() : now()->startOfMonth();

            $end = request('month') ? Carbon::parse(request('month'))->endOfMonth() : now()->endOfMonth();

            $types = Type::isType('expense')->whereHas('expenses')->get()->sortByDesc(function($type) use($start, $end){
                return $type->getExpenseTotal($start, $end);
            });

            return view('admin.expenses.total', [
                'start' => $start,
                'end' => $end,
                'period' => $period,
                'types' => $types,
            ]);
        }else{
            return view('admin.expenses.noexpense', [
                'message' => "Expense များမရှိသေးပါ။"
            ]);
        }
    }

    public function totalPrint()
    {
        $data = json_decode(request('data'), true);

        return view('admin.expenses.print', [
            'data' => $data,
            'start' => request('start'),
            'end' => request('end'),
            'month' => Carbon::parse(request('month'))
        ]);
    }

}
