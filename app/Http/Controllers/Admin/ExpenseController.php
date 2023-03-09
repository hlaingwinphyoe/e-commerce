<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Supplier;
use App\Models\Type;
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
}
