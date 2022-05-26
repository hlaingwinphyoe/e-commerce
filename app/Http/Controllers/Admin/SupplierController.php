<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;


class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-supplier')->only(['index']);
        $this->middleware('permissions:create-supplier')->only(['create', 'store']);
        $this->middleware('permissions:edit-supplier')->only(['edit', 'update']);
        $this->middleware('permissions:delete-supplier')->only(['destroy']);
    }
    
    public function index()
    {
        $suppliers = Supplier::filterOn()->latest()->paginate(20);

        return view('admin.suppliers.index')->with([
            'suppliers' => $suppliers,
        ]);
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.suppliers.index')->with('message', 'Supplier Created.');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('admin.suppliers.edit')->with([
            'supplier' => $supplier,
        ]);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name' => 'required',
        ]);

        $supplier->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        if ($request->featured) {
            $supplier->medias()->sync($request->featured);
        }
        return redirect($request->session()->get('prev_route'))->with('message', 'Supplier Updated.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Deleted successfully!');
    }
}
