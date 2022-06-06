<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderNotificationController extends Controller
{
    public function getOrderNoti()
    {
        $notifications = auth()->user()->unreadNotifications()
            // ->where('type', 'App\Notifications\OrderCreatedToAdmin')
            ->latest()
            ->take(10)
            ->get();

        $count = auth()->user()->unreadNotifications()
            // ->where('type', 'App\Notifications\OrderCreatedToAdmin')
            ->count();

        return response()->json([
            'data' => $notifications,
            'count' => $count,
        ]);
    }
}
