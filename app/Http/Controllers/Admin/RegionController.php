<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Support\Str;

class RegionController extends Controller
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
        $regions = Region::filterOn()->orderBy('name')->paginate(20);

        $countries = Country::get();

        return view('admin.regions.index')->with([
            'regions' => $regions,
            'countries' => $countries,
        ]);
    }

    public function create() 
    {
        $countries = Country::all();
        return view('admin.regions.create',compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required:unique:regions,name'
        ]);

        $region = Region::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'mm_name' => $request->mm_name,
            'country_id' => $request->country,
            'desc' => $request->desc
        ]);

        return redirect()->route('admin.regions.index')->with('message', 'Region Stored Successfully!');
    }


    public function edit($id) 
    {
        $region = Region::findOrFail($id);
        $countries = Country::all();

        return view('admin.regions.edit')->with([
            'region' => $region,
            'countries' => $countries
        ]);
    }

    public function update(Request $request, $id)
    {
        $region = Region::findOrFail($id);

        $request->validate([
            'name' => 'required:unique:regions,'.$region->id
        ]);

        $region->update([
            'name' => $request->name,
            'mm_name' => $request->mm_name,
            'country_id' => $request->country,
            'desc' => $request->desc
        ]);

        return redirect()->route('admin.regions.index')->with('message', 'Region Updated Successfully!');
    }

    public function destroy($id)
    {
        $region = Region::findOrFail($id);

        $region->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Region Deleted Successfully!');
    }
}
