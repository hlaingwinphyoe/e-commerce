<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(){
        $notifications = auth()->user()->unreadNotifications()->latest()->get();

        return view('admin.notifications.index',compact('notifications'));
    }

    public function show($id){
        $notification = auth()->user()->unreadNotifications()->findOrFail($id);

        $notification->markAsRead();

        if($notification->type == 'App\Notifications\OrderCreatedToAdmin') {
            return redirect()->route('admin.orders.edit',$notification->data['order']['id']);
        }else if($notification->type == 'App\Notifications\OrderNotiToUser') {
            return redirect()->route('admin.user-orders.show',$notification->data['order']['id']);
        }else if($notification->type == 'App\Notifications\GiftRequestedToAdmin') {
            return redirect()->route('admin.gift-logs.index');
        }else if($notification->type == 'App\Notifications\GiftAcceptedToUser') {
            return redirect()->route('admin.show-gifts');
        }

        
    }

    public function markAsRead($id){
        $notification = auth()->user()->unreadNotifications()->findOrFail($id);

        $notification->markAsRead();

        return redirect()->route('admin.orders.edit',$notification->data['order']['id']);
    }

    public function readall()
    {
        $notifications = auth()->user()->notifications()->get();

        foreach ($notifications as $noti) {
            $noti->delete();
        }

        return redirect()->back()->with('message', 'Notifications was successfully deleted');
    }
}
