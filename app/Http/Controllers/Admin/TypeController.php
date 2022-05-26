<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Maintype;
use App\Models\Type;
use Illuminate\Support\Str;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::filterOn()->orderBy('priority')->orderBy('name')->paginate(20);

        $categories = Type::where('parent_id', 0)->orderBy('name')->get();

        $maintypes = Maintype::orderBy('name')->get();

        return view('admin.types.index')->with([
            'types' => $types,
            'maintypes' => $maintypes,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $maintypes = Maintype::orderBy('slug')->get();

        $types = Type::where('parent_id', 0)->orderBy('name')->get();

        return view('admin.types.create')->with([
            'maintypes' => $maintypes,
            'types' => $types
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:types,name',
            // 'maintype' => 'required'
        ]);

        $type = Type::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'desc' => $request->desc,
            'parent_id' => $request->parent_id ?? 0,
            'user_id' => auth()->user()->id
        ]);

        $maintype = Maintype::first();
        if ($maintype) {
            $type->maintypes()->sync($maintype->id);
        }

        if ($request->featured) {
            $type->medias()->sync($request->featured);
        }

        if ($request->icon_name) {
            $type->medias()->create([
                'name' => $request->icon_name,
                'slug' => uniqid($request->icon_name . '_'),
                'url' => '/images/icons/' . $request->icon_name,
                'ext' => 'png',
                'type' => 'category-icon',
            ]);
        }

        return redirect()->route('admin.types.index')->with('message', 'Type Created.');
    }

    public function edit(Type $type)
    {
        $maintypes = Maintype::orderBy('slug')->get();

        $types = Type::where('id', '!=', $type->id)->where('parent_id', 0)->orderBy('name')->get();

        return view('admin.types.edit')->with([
            'type' => $type,
            'maintypes' => $maintypes,
            'types' => $types
        ]);
    }

    public function update(Request $request, $id)
    {
        $type = Type::findOrFail($id);

        $request->validate([
            'name' => 'required',
            // 'maintype' => 'required'
        ]);

        $type->update([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'desc' => $request->desc,
            'parent_id' => $request->parent_id ?? $type->parent_id,
            'user_id' => auth()->user()->id
        ]);

        // $type->maintypes()->sync($request->maintype);

        if ($request->featured) {
            $type->medias()->sync($request->featured);
        }

        if ($request->icon_name) {
            $type->medias()->create([
                'name' => $request->icon_name,
                'slug' => uniqid($request->icon_name . '_'),
                'url' => '/images/icons/' . $request->icon_name,
                'ext' => 'png',
                'type' => 'category-icon',
            ]);
        }

        return redirect($request->session()->get('prev_route'))->with('message', 'Type Updated.');
    }

    public function destroy($id)
    {
        $type = Type::findOrFail($id);

        $type->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Deleted successfully!');
    }

    public function changePriority(Request $request, $id)
    {
        $type = Type::findOrFail($id);

        $type->update([
            'priority' => $request->priority
        ]);

        return redirect($request->session()->get('prev_route'))->with('message', 'Priority Changed');
    }
}
