<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DeliFee;
use App\Models\Region;

class DeliFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-delifee')->only(['index', 'show']);
        $this->middleware('permissions:create-delifee')->only(['create', 'store']);
        $this->middleware('permissions:edit-delifee')->only(['edit', 'update']);
        $this->middleware('permissions:delete-delifee')->only('destroy');
    }

    public function index()
    {
        $delifees = DeliFee::orderBy('amt')->paginate(20);

        return view('admin.delifees.index')->with([
            'delifees' => $delifees
        ]);
    }

    public function create()
    {
        $regions = Region::isEnabled()->orderBy('name')->get();

        return view('admin.delifees.create')->with([
            'regions' => $regions
        ]);
    }

    public function store(Request $request) 
    {
        $request->validate([
            'amt' => 'required'
        ]);

        $delifee = DeliFee::create([
            'amt' => $request->amt,
            'user_id' => auth()->user()->id
        ]);

        if($request->townships) {
            $delifee->townships()->sync($request->townships);
        }

        return redirect()->route('admin.delifees.index')->with('message', 'DeliFee Created Successfully');
    }

    public function edit($id)
    {   
        $delifee = DeliFee::findOrFail($id);

        $regions = Region::isEnabled()->orderBy('name')->get();

        return view('admin.delifees.edit')->with([
            'delifee' => $delifee,
            'regions' => $regions
        ]);
    }

    public function update(Request $request, $id) 
    {
        $delifee = DeliFee::findOrFail($id);

        $request->validate([
            'amt' => 'required'
        ]);

        $delifee->update([
            'amt' => $request->amt,
            'user_id' => auth()->user()->id
        ]);

        if($request->townships) {
            $delifee->townships()->sync($request->townships);
        }

        return redirect($request->session()->get('prev_route'))->with('message', 'DeliFee Updated Successfully');
    }

    public function destroy($id)
    {
        $delifee = DeliFee::findOrFail($id);

        $delifee->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'DeliFee Deleted Successfully');
    }
}
