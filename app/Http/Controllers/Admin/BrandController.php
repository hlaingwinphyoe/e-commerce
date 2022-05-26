<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Brand;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::filterOn()->orderBy('name')->paginate(20);

        return view('admin.brands.index')->with([
            'brands' => $brands,
        ]);
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name',
        ]);

        $brand = Brand::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'desc' => $request->desc,
            'user_id' => auth()->user()->id
        ]);

        if ($request->featured) {
            $brand->medias()->sync($request->featured);
        }

        return redirect()->route('admin.brands.index')->with('message', 'Brand Created.');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);

        return view('admin.brands.edit')->with([
            'brand' => $brand,
        ]);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:brands,name,' . $brand->id,
        ]);

        $brand->update([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'desc' => $request->desc,
            'user_id' => auth()->user()->id
        ]);

        if ($request->featured) {
            $brand->medias()->sync($request->featured);
        }

        return redirect($request->session()->get('prev_route'))->with('message', 'Brand Updated.');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        $brand->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Deleted successfully!');
    }
}
