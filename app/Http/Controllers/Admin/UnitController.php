<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use App\Models\Unit;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-unit')->only(['index', 'show']);
        $this->middleware('permissions:create-unit')->only(['create', 'store']);
        $this->middleware('permissions:edit-unit')->only(['edit', 'update']);
        $this->middleware('permissions:delete-unit')->only('destroy');
    }

    public function index()
    {
        $units = Unit::orderBy('name')->paginate(20);

        return view('admin.units.index')->with([
            'units' => $units
        ]);
    }

    public function create()
    {
        return view('admin.units.create')->with([
            //
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:units,name',
        ]);

        $unit = Unit::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
        ]);

        return redirect()->route('admin.units.index')->with('message', 'Unit Created.');
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);

        return view('admin.units.edit')->with([
            'unit' => $unit
        ]);
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:units,name,' . $unit->id,
        ]);


        $unit->update([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
        ]);

        return redirect($request->session()->get('prev_route'))->with('message', 'Unit Updated.');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);

        $unit->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Deleted successfully!');
    }
}
