<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Township;
use App\Models\Region;
use App\Imports\TownshipImport;
use Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TownshipController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-region')->only(['index', 'show']);
        $this->middleware('permissions:create-region')->only(['create', 'store']);
        $this->middleware('permissions:edit-region')->only(['edit', 'update']);
        $this->middleware('permissions:delete-region')->only('destroy');
    }

    
    public function index() 
    {
        $townships = Township::filterOn()->orderBy('name')->paginate(20);

        $regions = Region::orderBy('name')->get();

        return view('admin.townships.index')->with([
            'townships' => $townships,
            'regions' => $regions,
        ]);
    }

    public function create() 
    {
        $regions = Region::orderBy('name')->get();

        return view('admin.townships.create')->with([
            'regions' => $regions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required:unique:townships,name',
            'region_id' => 'required'
        ]);

        $township = Township::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'mm_name' => $request->mm_name,
            'desc' => $request->desc,
            'region_id' => $request->region_id,
        ]);

        return redirect()->route('admin.townships.index')->with('message', 'Township Stored Successfully!');
    }


    public function edit($id) 
    {
        $township = Township::findOrFail($id);

        $regions = Region::orderBy('name')->get();

        return view('admin.townships.edit')->with([
            'township' => $township,
            'regions' => $regions,
        ]);
    }

    public function update(Request $request, $id)
    {
        $township = Township::findOrFail($id);

        $request->validate([
            'name' => 'required:unique:townships,'.$township->id,
            'region_id' => 'required'
        ]);

        $township->update([
            'name' => $request->name,
            'mm_name' => $request->mm_name,
            'desc' => $request->desc,
            'region_id' => $request->region_id,
        ]);

        return redirect()->route('admin.townships.index')->with('message', 'Township Updated Successfully!');
    }

    public function destroy($id)
    {
        $township = Township::findOrFail($id);

        $township->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Township Deleted Successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'files' => 'required|mimes:xlsx,application/csv,application/excel|min:0|max:5024',
        ]);

        $file = $request->file('files');
        $extension = $file->getClientOriginalExtension();
        $file_name = 'townships' . time() . '.' . $extension;
        $path = $request->file('files')->storeAs('tmp', $file_name);

        Excel::import(new TownshipImport, storage_path('app/' . $path));

        Storage::delete($path);

        return redirect()->route('admin.townships.index')->with('message', 'Township Import Success');
    }
}
