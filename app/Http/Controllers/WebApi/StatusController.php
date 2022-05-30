<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::isType(request()->type)->orderBy('priority')->orderBy('name')->get();

        return response()->json($statuses);
    }
}
