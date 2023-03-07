<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use Illuminate\Support\Str;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::filterOn()->latest()->paginate(20);

        return view('admin.currencies.index')->with([
            'currencies' => $currencies
        ]);
    }

    public function create()
    {
        return view('admin.currencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:currencies,name',
        ]);

        $currency = Currency::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
        ]);

        return redirect()->route('admin.currencies.index')->with('message', 'Currency Created.');
    }

    public function edit($id)
    {
        $currency = Currency::findOrFail($id);

        return view('admin.currencies.edit')->with([
            'currency' => $currency
        ]);
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:currencies,name,'.$id,
        ]);

        $currency = $currency->update([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
        ]);

        return redirect()->route('admin.currencies.index')->with('message', 'Currency Updated.');
    }

    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);

        $currency->delete();

        return redirect()->back()->with('message', 'Deleted successfully!');
    }
}
