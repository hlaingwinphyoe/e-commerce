<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gift;

class GiftController extends Controller
{
    public function index()
    {
        $gifts = Gift::filterOn()->select(['id', 'name'])->orderBy('name')->get();

        return response()->json($gifts);
    }
}
