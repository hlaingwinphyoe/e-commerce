<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;

class CustomerController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->user()->id)->isType('Customer')->filterOn()->orderBy('name')->paginate(20);

        $roles = Role::isType('Customer')->notAdmin()->orderBy('name')->get();


        return view('admin.customers.index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $roles = Role::isType('Customer')->notAdmin()->orderBy('name')->get();

        return view('admin.customers.create')->with([
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            // 'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'password' => bcrypt($request->password),
            'password' => bcrypt('password-123'),
            'role_id' => $request->role_id,
        ]);

        if($request->address) {
            $user->addresses()->create([
                'name' => $request->address
            ]);
        }

        return redirect()->route('admin.customers.index')->with('message', 'Customer created successfully!');
    }
}
