<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Media;
use App\Models\MainFeature;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Media::where('type', 'slides')->latest()->get();

        return view('admin.slides.index')->with([
            'slides' => $slides
        ]);
    }
}
