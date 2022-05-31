<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Type;

class MaintypeController extends Controller
{
    public function index()
    {
        $maintypes = Type::where('parent_id', '!=', 0)->filterOn()->orderBy('name')->get();

        return response()->json($maintypes);
    }
}
