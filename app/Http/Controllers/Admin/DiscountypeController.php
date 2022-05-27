<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DiscountType;
use App\Models\Status;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DiscountypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-discount-type')->only(['index', 'show']);
        $this->middleware('permissions:create-discount-type')->only(['create', 'store']);
        $this->middleware('permissions:edit-discount-type')->only(['edit', 'update']);
        $this->middleware('permissions:delete-discount-type')->only('destroy');
    }

    public function index()
    {
        $discountypes = DiscountType::filterOn()->latest()->paginate(20);

        return view('admin.discountypes.index')->with([
            'discountypes' => $discountypes
        ]);
    }

    public function create()
    {
        $statuses = Status::isType('price')->get();

        return view('admin.discountypes.create')->with([
            'statuses' => $statuses
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:discountypes,name',
            'amt' => 'required|numeric',
            'status' => 'required|exists:statuses,id'
        ]);

        $discountype = DiscountType::create([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'desc' => $request->desc,
            'start_date' => $request->start_date ?? now(),
            'end_date' => $request->end_date,
            'amt' => $request->amt,
            'status_id' => $request->status,
        ]);

        return redirect()->route('admin.discountypes.index')->with('message', 'discountype Created.');
    }

    public function edit($id)
    {
        $discountype = DiscountType::findOrFail($id);

        $statuses = Status::isType('price')->get();

        return view('admin.discountypes.edit')->with([
            'discountype' => $discountype,
            'statuses' => $statuses
        ]);
    }

    public function update(Request $request, $id)
    {
        $discountype = DiscountType::findOrFail($id);

        $old_expired = Carbon::parse($discountype->end_date);

        $request_expired = Carbon::parse($request->end_date);

        $request->validate([
            'name' => 'required|unique:discountypes,name,' . $discountype->id,
            'amt' => 'required|numeric',
            'status' => 'required|exists:statuses,id'
        ]);

        $discountype->update([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
            'desc' => $request->desc,
            'start_date' => $request->start_date ?? $discountype->start_date,
            'end_date' => $request->end_date,
            'amt' => $request->amt,
            'status_id' => $request->status,
        ]);

        if($old_expired !== $request_expired) {
            $discountype->updateExpiredDate();
        }

        return redirect($request->session()->get('prev_route'))->with('message', 'discountype Updated.');
    }

    public function destroy($id)
    {
        $discountype = DiscountType::findOrFail($id);

        $discountype->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Deleted successfully!');
    }
}
