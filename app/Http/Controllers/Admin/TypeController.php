<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Type;
use Illuminate\Support\Str;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-type')->only(['index', 'show']);
        $this->middleware('permissions:create-type')->only(['create', 'store']);
        $this->middleware('permissions:edit-type')->only(['edit', 'update']);
        $this->middleware('permissions:delete-type')->only('destroy');
    }

    public function index()
    {
        $types = Type::filterOn()->orderBy('priority')->orderBy('name')->paginate(20);

        $categories = Type::where('parent_id', 0)->orderBy('name')->get();

        return view('admin.types.index')->with([
            'types' => $types,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $types = Type::where('parent_id', 0)->orderBy('name')->get();

        return view('admin.types.create')->with([
            'types' => $types
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:types,name',
        ]);

        $type = Type::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'desc' => $request->desc,
            'parent_id' => $request->parent_id ?? 0,
            'user_id' => auth()->user()->id,
            'type' => 'cate'
        ]);

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
        $types = Type::where('id', '!=', $type->id)->where('parent_id', 0)->orderBy('name')->get();

        return view('admin.types.edit')->with([
            'type' => $type,
            'types' => $types
        ]);
    }

    public function update(Request $request, $id)
    {
        $type = Type::findOrFail($id);

        $request->validate([
            'name' => 'required',
        ]);

        $type->update([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'desc' => $request->desc,
            'parent_id' => $request->parent_id ?? $type->parent_id,
            'user_id' => auth()->user()->id,
            'type' => 'cate'
        ]);

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
