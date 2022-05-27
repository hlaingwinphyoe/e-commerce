<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use App\Imports\UserImport;
use Excel;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-user')->only(['index', 'show']);
        $this->middleware('permissions:create-user')->only(['create', 'store']);
        $this->middleware('permissions:edit-user')->only(['edit', 'update']);
        $this->middleware('permissions:delete-user')->only('destroy');
    }
    
    public function index()
    {
        $users = User::where('id', '!=', auth()->user()->id)->isType('Operation')->filterOn()->latest()->paginate(20);

        $roles = Role::isType('Operation')->orderBy('name')->get();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function customer()
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
        $roles = Role::isType('Operation')->notAdmin()->orderBy('name')->get();

        return view('admin.users.create')->with([
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'role_id' => 'required'
        ]);

        $role = Role::where('slug', 'guest')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.users.index')->with('message', 'User created successfully!');
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update(['role_id' => $request->role]);

        return redirect($request->session()->get('prev_route'))->with('message', 'User Role Updated');
        
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'role_id' => $request->role_id,
        ]);

        return redirect()->back()->with('message', $user->name . " 's role is updated!");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $orders = Order::where('customer_id', $user->id)->get();

        $user_orders = Order::where('user_id', $user->id)->get();

        foreach($orders as $order) {
            $order->update(['customer_id' => null]);
        }

        foreach($user_orders as $order) {
            $order->update(['user_id' => null]);
        }

        $user->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'User was successfully deleted');
    }

    public function changePassword(Request $request, $id)
    {
    $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required'
        ]);

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return redirect()->back()->with('message', "Changed password successfully. ");
    }

    public function import(Request $request)
    {
        $request->validate([
			'files' => 'required|mimes:xlsx,application/csv,application/excel|min:0|max:5024',            
		]);   

        // dd('here');     

		$file = $request->file('files');
		$extension = $file->getClientOriginalExtension();
		$file_name = 'users' . time() . '.' . $extension;
		$path = $request->file('files')->storeAs('tmp', $file_name);

        Excel::import(new UserImport, storage_path('app/' . $path));

        Storage::delete($path);

        return redirect()->back();
    }
}
