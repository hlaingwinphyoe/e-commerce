<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Imports\CouponsImport;
use App\Models\Coupon;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-coupon')->only(['index', 'show']);
        $this->middleware('permissions:create-coupon')->only(['create', 'store']);
        $this->middleware('permissions:edit-coupon')->only(['edit', 'update']);
        $this->middleware('permissions:delete-coupon')->only('destroy');
    }

    public function index()
    {
        $coupons = Coupon::filterOn()->orderBy('is_used')->paginate(20);

        $maintypes = Type::orderBy('name')->get();

        return view('admin.coupons.index')->with([
            'coupons' => $coupons,
            'maintypes' => $maintypes
        ]);
    }

    public function create()
    {
        $maintypes = Type::orderBy('name')->get();

        return view('admin.coupons.create')->with([
            'maintypes' => $maintypes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons',
            'amt' => 'required',
            'type' => 'required',
        ]);

        $coupon = Coupon::create([
            'code' => $request->code,
            'value' => $request->type == 'fixed' ? $request->amt : null,
            'percent_off' => $request->type == 'percent' ? $request->amt : null,
            'type' => $request->type,
            'type_id' => 1,
            // 'type_id' => $request->type_id ?? null,
            'expired' => $request->expired,
            'user_id' => auth()->user()->id
        ]);

        $coupon->medias()->sync($request->featured);

        return redirect()->route('admin.coupons.index')->with('message', 'Coupon was successfully created.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);

        $maintypes = Type::orderBy('name')->get();

        return view('admin.coupons.edit')->with([
            'coupon' => $coupon,
            'maintypes' => $maintypes
        ]);
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'amt' => 'required',
            'type' => 'required',
        ]);

        $coupon->update([
            'code' => $request->code,
            'value' => $request->type == 'fixed' ? $request->amt : $coupon->value,
            'percent_off' => $request->type == 'percent' ? $request->amt : $coupon->percent_off,
            'type' => $request->type,
            // 'type_id' => $request->type_id ?? $coupon->type_id,
            'type_id' => 1,
            'expired' => $request->expired,
            'user_id' => auth()->user()->id
        ]);

        $coupon->medias()->sync($request->featured);

        return redirect($request->session()->get('prev_route'))->with('message', 'Coupon was successfully updated.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Coupon was successfully deleted.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'files' => 'required|mimes:xlsx,application/csv,application/excel|min:0|max:5024',
        ]);

        $file = $request->file('files');
        $extension = $file->getClientOriginalExtension();
        $file_name = 'coupons_' . time() . '.' . $extension;
        $path = $request->file('files')->storeAs('tmp', $file_name);

        Excel::import(new CouponsImport, storage_path('app/' . $path));

        Storage::delete($path);

        return redirect()->route('admin.coupons.index')->with('message', 'Coupon Import Success');
    }

    public function generateCoupon(Request $request)
    {
        $request->validate([
            'amt' => 'required',
            'type' => 'required',
        ]);
        $index = 0;
        while ($index < $request->count) {
            $index++;
            $coupon = Coupon::create([
                'code' => strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 6)),
                'value' => $request->type == 'fixed' ? $request->amt : null,
                'percent_off' => $request->type == 'percent' ? $request->amt : null,
                'type' => $request->type,
                'expired' => $request->expired,
                'user_id' => auth()->user()->id
            ]);
        }

        return redirect()->back()->with('message', 'Coupon codes successfully generated.');
    }
}
