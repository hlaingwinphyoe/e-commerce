<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\Status;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-delivery')->only(['index', 'show']);
        $this->middleware('permissions:create-delivery')->only(['create', 'store']);
        $this->middleware('permissions:edit-delivery')->only(['edit', 'update']);
        $this->middleware('permissions:delete-delivery')->only('destroy');
    }

    public function index()
    {
        $deliveries = Delivery::filterOn()->orderBy('name')->paginate(20);

        return view('admin.deliveries.index')->with([
            'deliveries' => $deliveries,
        ]);
    }

    public function create()
    {
        return view('admin.deliveries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:deliveries,name',
        ]);

        $delivery = Delivery::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        if ($request->featured) {
            $delivery->medias()->sync($request->featured);
        }

        return redirect()->route('admin.deliveries.index')->with('message', 'Delivery Created.');
    }

    public function show(Request $request,$id)
    {
        $delivery = Delivery::findOrFail($id);
        
        if ($request->from_date && $request->to_date) {
            $orders = $delivery->orders()
            ->whereBetween('date', [$request->from_date , $request->to_date])
            ->get();
        }elseif($request->from_date){
            $orders = $delivery->orders()->whereDate('date','>=', $request->from_date)->get();
        }elseif($request->to_date){
            $orders = $delivery->orders()->whereDate('date','<=', $request->to_date)->get();
        }
         else {
            $orders = $delivery->orders()->whereDate('date', Carbon::now())->get();
        }
        $total_sum = $orders->sum('price');
        $statuses = Status::whereIn('slug',['completed','cancel'])->get();
        return view('admin.deliveries.show')->with([
            'delivery' => $delivery,
            'orders' => $orders,
            'statuses' => $statuses,
            'total_sum' => $total_sum
        ]);
    }

    public function edit($id)
    {
        $delivery = Delivery::findOrFail($id);

        return view('admin.deliveries.edit')->with([
            'delivery' => $delivery,
        ]);
    }

    public function update(Request $request, $id)
    {
        $delivery = Delivery::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:deliveries,name,' . $delivery->id,
        ]);

        $delivery->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        if ($request->featured) {
            $delivery->medias()->sync($request->featured);
        }

        return redirect($request->session()->get('prev_route'))->with('message', 'Delivery Updated.');
    }

    public function destroy($id)
    {
        $delivery = Delivery::findOrFail($id);

        $delivery->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Deleted successfully!');
    }
}
