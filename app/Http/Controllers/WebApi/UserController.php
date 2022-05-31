<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Order;

class UserController extends Controller
{
    public function index()
    {
        $users = User::filterOn()->get();

        return response()->json($users);
    }
}
