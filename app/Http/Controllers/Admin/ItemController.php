<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Currency;
use App\Models\DiscountType;
use App\Models\Item;
use App\Models\Media;
use App\Models\Role;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-item')->only(['index', 'show']);
        $this->middleware('permissions:create-item')->only(['create', 'store']);
        $this->middleware('permissions:edit-item')->only(['edit', 'update']);
        $this->middleware('permissions:delete-item')->only('destroy');
    }

    public function index()
    {
        if (request('status') && request('status') == 'trashed') {
            $items = Item::onlyTrashed()->filterOn()->latest()->paginate(20);
        } else {
            $items = Item::filterOn()->latest()->paginate(20);
        }

        $types = Type::orderBy('name')->get();

        $discountypes = DiscountType::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        $item_count = Item::withTrashed()->count();

        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.items.index')->with([
            'items' => $items,
            'types' => $types,
            'discountypes' => $discountypes,
            'brands' => $brands,
            'item_count' => $item_count,
            'suppliers' => $suppliers
        ]);
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);

        return view('admin.items.show')->with([
            'item' => $item,
        ]);
    }

    public function create()
    {
        $types = Type::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        $units = Unit::orderBy('name')->get();

        return view('admin.items.create')->with([
            'types' => $types,
            'brands' => $brands,
            'units' => $units
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:items',
        ]);

        $item = DB::transaction(function () use ($request) {
            $item = Item::create([
                'name' => $request->name,
                'code' => $request->code ?? uniqid(),
                'desc' => $request->desc,
                'spec' => $request->spec,
                'min_stock' => $request->min_stock ?? 1,
                'stock' => $request->stock ?? 1,
                'user_id' => auth()->user()->id,
                'per_unit' => $request->per_unit,
                'unit_id' => $request->unit,
                'brand_id' => $request->brand
            ]);

            $item->skus()->create([
                "code" => $request->code ?? $item->code,
                "item_name" => $item->name,
                "item_id" => $item->id,
                "pure_price" => 0,
            ]);

            if ($request->type) {
                $item->types()->sync($request->type);
            }

            return $item;
        });

        return redirect()->route('admin.items.edit', $item->id);
    }

    public function edit($id)
    {
        $item = Item::with([
            'wastes', 'costs', 'pricings.role', 'pricings.status'
        ])->findOrFail($id);

        $types = Type::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        $roles = Role::notSeller()->orderBy('priority')->get();

        $discountypes = DiscountType::get();

        //        $attributes = Status::isType('attribute')->pluck('name');

        $statuses = Status::isType('price')->get();

        $discounts = $item->discounts()->with(['role', 'status'])->get();

        $units = Unit::orderBy('name')->get();

        $suppliers = Supplier::orderBy('name')->get();

        $item_attributes = $item->attributes()->with(['values'])->get();
        $currencies = Currency::all();

        $exchange_rates = $currencies;

        $exchange_rates = $exchange_rates->map(function ($currency) {
            return $currency->exchangerates()->with('currency')->latest()->first();
        });


        return view('admin.items.edit')->with([
            'item' => $item,
            'types' => $types,
            'brands' => $brands,
            'roles' => $roles,
            'discountypes' => $discountypes,
            'statuses' => $statuses,
            'discounts' => $discounts,
            'units' => $units,
            'suppliers' => $suppliers,
            'item_attributes' => $item_attributes,
            'exchange_rates' => $exchange_rates
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:items,name,' . $item->id,
            'pure_price' => 'required_with:currency',
            'currency' => 'required_with:pure_price'
        ]);

        DB::transaction(function () use ($request, $item) {
            $item->update([
                'name' => $request->name ?? $item->name,
                'code' => $request->code ?? $item->code,
                'desc' => $request->desc ?? $item->desc,
                'spec' => $request->spec ?? $item->spec,
                'min_stock' => $request->min_stock ?? $item->min_stock,
                'stock' => $request->stock ?? $item->stock,
                'user_id' => auth()->user()->role->type == 'technician' ? $item->user_id : auth()->user()->id,
                'per_unit' => $request->per_unit,
                'pure_price' => $request->pure_price ?? $item->pure_price,
                'currency_id' => $request->currency ?? $item->currency_id,
                'unit_id' => $request->unit,
                'brand_id' => $request->brand,
            ]);    

            $item->costs()->sync($request->costs);

            $item->pricings()->sync($request->pricings);

            $item->wastes()->sync($request->wastes);

            $item->skus()->update([
                "code" => $request->code ?? $item->code,
                "item_name" => $item->name,
                "pure_price" => $item->getPureCost(),
                "buy_price" => $item->getRawCost(),
                "currency_id" => $item->currency_id
            ]);

            if ($request->type) {
                $item->types()->sync($request->type);
            }

            if ($request->featured) {
                $medias = Media::whereIn('id', $request->featured)->where('type', 'item')->pluck('id')->toArray();
                $item->medias()->sync($medias);
            }

            if ($request->discounts) {
                $item->discounts()->sync($request->discounts);
            }

            $attrs = $item->attributes()->whereDoesntHave('values')->delete();
        });

        return redirect()->route('admin.items.index')->with('message', 'Item was successfully updated.');

        // return redirect($request->session()->get('prev_route'))->with('message', 'Item was successfully updated.');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        $item->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Item was successfully deleted');
    }

    public function restore($id)
    {
        $item = Item::withTrashed()->findOrFail($id);

        $item->restore();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Item was successfully restored');
    }

    public function delete($id)
    {
        $item = Item::withTrashed()->findOrFail($id);

        DB::transaction(function () use ($item) {
            foreach ($item->skus as $sku) {
                $sku->delete();
            }
            $item->forceDelete();
        });


        return redirect(request()->session()->get('prev_route'))->with('message', 'Item was permanently deleted');
    }
}
