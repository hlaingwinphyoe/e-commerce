<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use App\Models\Supplier;
use App\Models\Sku;
use App\Models\Currency;
use App\Models\Status;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-inventory')->only(['index', 'show']);
        $this->middleware('permissions:create-inventory')->only(['create', 'store']);
        $this->middleware('permissions:edit-inventory')->only(['edit', 'update']);
        $this->middleware('permissions:delete-inventory')->only('destroy');
    }

    public function index()
    {
        $inventories = Inventory::filterOn()->latest()->paginate(20);

        $suppliers = Supplier::orderBy('name')->get();

        $payment_statuses = Status::isType('payment-type')->get();

        return view('admin.inventories.index', [
            'inventories' => $inventories,
            'suppliers' => $suppliers,
            'payment_statuses' => $payment_statuses
        ]);
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.inventories.create', [
            'suppliers' => $suppliers,
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
        ]);

        $inventory_month = Carbon::now()->format('ym');

        $latest_inventory = Inventory::where('inventory_month', intval($inventory_month))->orderBy('inventory_no', 'desc')->first();

        $inventory_no = $latest_inventory ? $latest_inventory->inventory_no + 1 : intval($inventory_month . '00001');

        $inventory = Inventory::create([
            'inventory_month' => $inventory_month,
            'inventory_no' => $inventory_no,
            'date' => $request->date ?? now(),
            'supplier_id' => $request->supplier_id,
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('admin.inventories.edit', $inventory->id);
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);

        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.inventories.edit', [
            'inventory' => $inventory,
            'suppliers' => $suppliers,
        ]);
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'date' => 'required|date'
        ]);

        $inventory->update([
            'supplier_id' => $request->supplier_id ?? $inventory->supplier_id,
            'date' => $request->date ?? $inventory->date
        ]);

        return redirect()->back()->with('message', 'Updated Successfully');
    }

    public function publishById($id)
    {
        $inventory = Inventory::findOrFail($id);

        $inventory->update([
            'is_published' => 1,
        ]);

        $inventory->stockUpdate('add');
        $inventory->skuAmountUpdate();

        return redirect()->back()->with('message', "Closed Voucher!");
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);

        DB::transaction(function () use ($inventory) {
            if ($inventory->is_published) {
                $inventory->stockUpdate('remove');
            }
        });

        $inventory->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Inventory deleted successfully.');
    }
}
