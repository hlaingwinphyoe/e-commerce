<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExpenseTypeController extends Controller
{
    public function index()
    {
        $expensetypes = Type::filterOn()->isType('expense')->orderBy('priority')->orderBy('name')->paginate(20);

        return view('admin.expensetypes.index')->with([
            'expensetypes' => $expensetypes,
        ]);
    }

    public function create()
    {
        return view('admin.expensetypes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:types,name',
        ]);

        $type = Type::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'desc' => $request->desc,
            'parent_id' => $request->parent_id ?? 0,
            'user_id' => auth()->user()->id,
            'type' => 'expense'
        ]);

        return redirect($request->session()->get('prev_route'))->with('message', 'Created Successfully');
    }

    public function edit($id)
    {
        $expensetype = Type::findOrFail($id);
        return view('admin.expensetypes.edit',compact('expensetype'));
    }

    public function update(Request $request, $id)
    {
        $expensetype = Type::findOrFail($id);

        $request->validate([
            'name' => 'required',
        ]);

        $expensetype->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'parent_id' => $request->parent_id ?? $expensetype->parent_id,
            'user_id' => auth()->user()->id,
        ]);
        return redirect($request->session()->get('prev_route'))->with('message', 'Updated Successfully.');
    }

    public function destroy($id)
    {
        $expensetype = Type::findOrFail($id);

        $expensetype->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Deleted successfully!');
    }

    public function changePriority(Request $request, $id)
    {
        $expensetype = Type::findOrFail($id);

        $expensetype->update([
            'priority' => $request->priority
        ]);

        return redirect($request->session()->get('prev_route'))->with('message', 'Priority Changed');
    }

}
