<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-role')->only(['index', 'show']);
        $this->middleware('permissions:create-role')->only(['create', 'store']);
        $this->middleware('permissions:edit-role')->only(['edit', 'update']);
        $this->middleware('permissions:delete-role')->only('destroy');
    }
    
    public function index()
    {
        $roles = Role::filterOn()->notDeveloper()->orderBy('type')->orderBy('name')->paginate(20);

        $permissions = Permission::all();

        return view('admin.roles.index', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function create()
    {
        $permissions = Permission::get()->groupBy('type');

        $roles = Role::notAdmin()->get();

        return view('admin.roles.create', [
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
        ]);

        $role = Role::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'type' => $request->type
        ]);

        $role->permissions()->sync($request->permis);

        // $role->permissions()->attach($request->permissions);

        return redirect()->route('admin.roles.index')->with('message', 'Role is created');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::get()->groupBy('type');

        $roles = Role::notAdmin()->get();

        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validate = $request->validate([
            'name' => 'required',
        ]);

        $role->update([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'type' => $request->type ?? $role->type
        ]);

        $role->permissions()->sync($request->permis);

        // $role->permissions()->detach();

        // $role->permissions()->attach($request->permissions);

        return redirect($request->session()->get('prev_route'))->with('message', 'Role update successfull');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        $role->permissions()->detach();

        $role->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Role deleted.');
    }
}
