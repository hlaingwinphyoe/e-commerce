<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BonusPoint;
use App\Models\Role;

class BonusPointController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-brand')->only(['index', 'show']);
        $this->middleware('permissions:create-brand')->only(['create', 'store']);
        $this->middleware('permissions:edit-brand')->only(['edit', 'update']);
        $this->middleware('permissions:delete-brand')->only('destroy');
    }

    public function index()
    {
        $bonuspoints = BonusPoint::filterOn()->orderBy('points')->paginate(20);

        $roles = Role::notSeller()->orderBy('name')->get();

        return view('admin.bonuspoints.index')->with([
            'bonuspoints' => $bonuspoints,
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $roles = Role::notSeller()->orderBy('name')->get();

        return view('admin.bonuspoints.create')->with([
            'roles' => $roles
        ]);
    }

    public function store(Request $request) 
    {
        $request->validate([
            'amount' => 'required',
            'points' => 'required',
            'role_id' => 'required'
        ]);

        $bonuspoint = BonusPoint::create([
            'amt' => $request->amount,
            'points' => $request->points,
            'role_id' => $request->role_id
        ]);

        return redirect()->route('admin.bonuspoints.index')->with('message', 'Bonuspoint successfully created.');
    }

    public function edit($id)
    {
        $bonuspoint = BonusPoint::findOrFail($id);

        $roles = Role::notSeller()->orderBy('name')->get();

        return view('admin.bonuspoints.edit')->with([
            'roles' => $roles,
            'bonuspoint' => $bonuspoint
        ]);
    }

    public function  update(Request $request, $id) 
    {
        $bonuspoint = BonusPoint::findOrFail($id);

        $request->validate([
            'amount' => 'required',
            'points' => 'required',
            'role_id' => 'required'
        ]);        

        $bonuspoint->update([
            'amt' => $request->amount,
            'points' => $request->points,
            'role_id' => $request->role_id
        ]);

        return redirect($request->session()->get('prev_route'))->with('message', 'Bonuspoint successfully created.');
    }

    public function destroy($id) 
    {
        $bonuspoint = BonusPoint::findOrFail($id);

        $bonuspoint->delete();

        return redirect()->back()->with('message', 'Deleted successfully!');
    }
}
