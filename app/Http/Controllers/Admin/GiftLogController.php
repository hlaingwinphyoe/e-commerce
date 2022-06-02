<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Notification;
use App\Notifications\GiftAcceptedToUser;

use App\Models\Gift;
use App\Models\UserGift;
use App\Models\Status;
use App\Models\Delivery;

class GiftLogController extends Controller
{
    public function index()
    {
		
    	$user_gifts = UserGift::filterOn()->whereHas('status', function($q){
			$q->where('slug', '!=', 'cancel');
		})->latest()->paginate(20);

    	$statuses = Status::where('type', 'order')->get();

		$deliveries = Delivery::orderBy('name')->get();

    	return view('admin.giftLog.index',[
    		'user_gifts' => $user_gifts,
    		'statuses' => $statuses,
			'deliveries' => $deliveries
    	]);
    }

    public function edit($id)
    {
    	$log = UserGift::findOrFail($id);
    	$statuses = Status::where('type', 'order')->get();

    	return view('admin.giftLog.edit', [
    		'log' => $log,
    		'statuses' => $statuses,
    	]);
    }

    public function update(Request $request, $id)
    {
    	$log = UserGift::findOrFail($id); 

    	$log->update([
    		'status_id' => $request->status_id,
    	]);

		Notification::send($log->user, new GiftAcceptedToUser($log));

    	return redirect()->back()->with('success', 'Gift status is updated');
    }

	public function giftDelivery(Request $request, $id)
	{
		$log = UserGift::findOrFail($id);

		$log->deliveries()->attach([$request->delivery_id => [
			'date' => now()
		]]);

		return redirect($request->session()->get('prev_route'))->with('message', 'Delivery Updated.');
	}
}
