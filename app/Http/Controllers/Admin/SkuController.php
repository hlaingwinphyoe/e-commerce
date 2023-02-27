<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StocksExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sku;
use App\Models\Type;
use App\Models\Brand;
use App\Models\Status;
use App\Models\Inventory;
use App\Models\Order;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SkuController extends Controller
{
    public function lowStockSkus()
    {
        $skus = Sku::with(['orders'])->filterOn()->orderBy('stock')->orderBy('data')->get()->filter(function ($sku) {
            return $sku->stock < $sku->min_stock;
        })->paginate(20);

        return view('admin.skus.low-stock')->with([
            'skus' => $skus
        ]);
    }

    public function index()
    {
        $skus = Sku::whereHas('item')->filteron();

//        if (request('sort') && request('sort') == 'asc') {
//            $skus = $skus->get()->sortBy(function ($sku) {
//                return $sku->item()->withTrashed()->first() ? $sku->item()->withTrashed()->first()->name : $sku->data;
//            })->values()->paginate(20);
//        } else if (request('sort') && request('sort') == 'desc') {
//            $skus = $skus->get()->sortByDesc(function ($sku) {
//                return $sku->item()->withTrashed()->first() ? $sku->item()->withTrashed()->first()->name : $sku->data;
//            })->values()->paginate(20);
//        } else {
//            $skus = $skus->paginate(20);
//        }

        if (\request('sort') && \request('sort') === 'asc'){
            $skus = $skus->orderBy('item_name','asc')->paginate(20);
        }elseif(\request('sort') && \request('sort') === 'desc'){
            $skus = $skus->orderBy('item_name','desc')->paginate(20);
        }else{
            $skus = $skus->paginate(20);
        }

        $types = Type::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        $statuses = Status::where('type', 'waste')->orderBy('name')->get();

        return view('admin.skus.index')->with([
            'skus' => $skus,
            'types' => $types,
            'brands' => $brands,
            'statuses' => $statuses,
        ]);
    }

    public function show($id)
    {
        $sku = Sku::findOrFail($id);

        $from_date = request('from_date') ? Carbon::parse(request('from_date')) : now()->startOfMonth();

        $to_date = request('to_date') ? Carbon::parse(request('to_date')) : now()->endOfMonth();

        $inventories = $sku->inventories()->where('is_published', 1)->whereDate('date', '>=', $from_date)->whereDate('date', '<=', $to_date)->latest()->get();

        // $orders = $sku->whereHas('orders', function ($query) use ($from_date, $to_date) {
        //     $query->whereHas('status', function ($query) {
        //         $query->where('slug', 'completed');
        //     })->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
        // })->latest()->get();

        $orders = Order::saleOrder()->filterOn()->fromTo()->orderBy('order_no', 'desc')->paginate(20);

        $wait_orders = $sku->whereHas('orders', function ($query) use ($from_date, $to_date) {
            $query->whereHas('status', function ($query) {
                $query->where('slug', 'order-confirmed');
            })->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
        })->latest()->get();

        $returns = $sku->returns()->whereDate('date', '>=', $from_date)->whereDate('date', '<=', $to_date)->latest()->get();

        $wastes = $sku->wastes()->whereDate('date', '>=', $from_date)->whereDate('date', '<=', $to_date)->latest()->get();

        $gift_inventories = $sku->gift_inventories()->whereDate('date', '>=', $from_date)->whereDate('date', '<=', $to_date)->latest()->get();

        return view('admin.skus.show')->with([
            'sku' => $sku,
            'inventories' => $inventories,
            'orders'  => $orders,
            'wait_orders' => $wait_orders,
            'returns' => $returns,
            'wastes' => $wastes,
            'gift_inventories' => $gift_inventories,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);
    }

    public function generateBarcode()
    {
        //
    }

    public function update(Request $request, $id)
    {
        $sku = Sku::findOrFail($id);

        $stock = ($sku->getTotalInventoriesCount() + $sku->getReturnCount() + $sku->getTotalWasteCount('adjust')) - ($sku->getTotalSalesCount() + $sku->getTotalWaitCount() + $sku->getGiftCount() + $sku->getTotalWasteCount());

        $sku->update([
            'stock' => $stock
        ]);

        return redirect()->back()->with('message', 'Stock Already Updated');
    }

    public function addStock(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required'
        ]);

        $sku = Sku::findOrFail($id);

        $inventory = Inventory::where('date', now())->first();

        if (!$inventory) {
            $inventory_month = Carbon::now()->format('ym');

            $latest_inventory = Inventory::where('inventory_month', intval($inventory_month))->orderBy('inventory_no', 'desc')->first();

            $inventory_no = $latest_inventory ? $latest_inventory->inventory_no + 1 : intval($inventory_month . '00001');

            $inventory = Inventory::create([
                'inventory_month' => $inventory_month,
                'inventory_no' => $inventory_no,
                'date' => now(),
                'user_id' => auth()->user()->id,
                'is_published' => 1
            ]);
        }

        DB::transaction(function () use ($request, $sku, $inventory) {
            $inventory->skus()->attach([$sku->id => [
                'qty' => $request->qty,
                'amount' => $request->buy_price,
                'rate' => 1,
                'currency_id' => 1,
            ]]);

            $sku->update([
                'stock' => $sku->stock + $request->qty,
                'buy_price' => $request->buy_price ?? $sku->buy_price
            ]);
        });

        return redirect()->back();
    }

    public function resetStock($id)
    {
        $sku = Sku::findOrFail($id);

        if($sku->stock > 0) {
            DB::transaction(function() use($sku) {
                $status = Status::where('slug', 'reset')->first();
                $sku->wastes()->create([
                    'amt' => $sku->stock,
                    'status_id' => $status->id,
                    'date' => now(),
                    'remark' => 'Reset',
                    'user_id' => auth()->user()->id
                ]);

                $sku->update(['stock' => 0]);
            });
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $sku = Sku::findOrFail($id);

        $sku->delete();

        return redirect()->back();
    }

    public function export()
    {
        $type = \request()->type;

        return Excel::download(new StocksExport($type), 'stocks.xlsx');
    }

}
