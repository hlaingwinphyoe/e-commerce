<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExchangeRate;
use App\Models\Currency;

class ExchangeRateController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permissions:access-exchangerate')->only(['index', 'show']);
        // $this->middleware('permissions:create-exchangerate')->only(['create', 'store']);
        // $this->middleware('permissions:edit-exchangerate')->only(['edit', 'update']);
        // $this->middleware('permissions:delete-exchangerate')->only('destroy');
    }

    public function index()
    {
        $exchangerates = ExchangeRate::filterOn()->latest()->paginate(20);

        $currencies = Currency::get();

        return view('admin.exchangerates.index')->with([
            'exchangerates' => $exchangerates,
            'currencies' => $currencies
        ]);
    }

    public function create()
    {
        $currencies = Currency::get();

        return view('admin.exchangerates.create')->with([
            'currencies' => $currencies
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'exchange_rate' => 'required_without:division_rate',
            'division_rate' => 'required_without:exchange_rate',
            'currency' => 'required|exists:currencies,id'
        ]);

        $exchangerate = ExchangeRate::create([
            'rate' => $request->division_rate ?? round(1/ $request->exchange_rate, 5),
            'mmk' => $request->exchange_rate ?? round(1 / $request->division_rate, 5),
            'currency_id' => $request->currency,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('admin.exchangerates.index')->with('message', 'Exchangerate Created.');
    }

    public function edit($id)
    {
        $exchangerate = ExchangeRate::findOrFail($id);

        $currencies = Currency::get();

        return view('admin.exchangerates.edit')->with([
            'exchangerate' => $exchangerate,
            'currencies' => $currencies
        ]);
    }

    public function update(Request $request, $id)
    {
        $exchangerate = ExchangeRate::findOrFail($id);

        $request->validate([
            'exchange_rate' => 'required_without:division_rate',
            'division_rate' => 'required_without:exchange_rate',
            'currency' => 'required|exists:currencies,id'
        ]);

        $exchangerate->update([
            'rate' => $request->division_rate ?? round(1/ $request->exchange_rate, 5),
            'mmk' => $request->exchange_rate ?? round(1 / $request->division_rate, 5),
            'currency_id' => $request->currency,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('admin.exchangerates.index')->with('message', 'Exchangerate Updated.');
    }

    public function destroy($id)
    {
        $exchangerate = ExchangeRate::findOrFail($id);

        $exchangerate->delete();

        return redirect()->back()->with('message', 'Deleted successfully!');
    }
}
