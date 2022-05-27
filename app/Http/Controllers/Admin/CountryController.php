<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Country;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-country')->only(['index', 'show']);
        $this->middleware('permissions:create-country')->only(['create', 'store']);
        $this->middleware('permissions:edit-country')->only(['edit', 'update']);
        $this->middleware('permissions:delete-country')->only('destroy');
    }
    
    public function index() 
    {
        $countries = Country::filterOn()->orderBy('name')->paginate(20);

        return view('admin.countries.index')->with([
            'countries' => $countries
        ]);
    }

    public function create() 
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required:unique:countries,name'
        ]);

        $country = Country::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'mm_name' => $request->mm_name,
            'desc' => $request->desc
        ]);

        return redirect($request->session()->get('prev_route'))->with('message', 'Country Stored Successfully!');
    }


    public function edit($id) 
    {
        $country = Country::findOrFail($id);

        return view('admin.countries.edit')->with([
            'country' => $country
        ]);
    }

    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);

        $request->validate([
            'name' => 'required:unique:countries,'.$country->id
        ]);

        $country->update([
            'name' => $request->name,
            'mm_name' => $request->mm_name,
            'desc' => $request->desc
        ]);

        return redirect($request->session()->get('prev_route'))->with('message', 'Country Updated Successfully!');
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);

        $country->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Country Deleted Successfully!');
    }
}
