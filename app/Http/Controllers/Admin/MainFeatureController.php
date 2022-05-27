<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MainFeature;
use Illuminate\Support\Facades\Storage;

class MainFeatureController extends Controller
{
    public function index()
    {
        $home_features = MainFeature::isType('home')->orderBy('disabled')->latest()->get();

        return view('admin.mainfeatures.index')->with([
            'home_features' => $home_features
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'link' => 'required'
        ]);

        $feature = MainFeature::create([
            'title' => $request->title,
            'link' => $request->link,
            'desc' => $request->desc,
            'type' => $request->type ?? 'home'
        ]);

        if ($request->featured) {
            $feature->medias()->sync($request->featured);
        }

        return redirect($request->session()->get('prev_route'))->with('message', 'Successfully created.');
    }

    public function update(Request $request, $id)
    {
        $feature = MainFeature::findOrFail($id);

        $feature->update([
            'title' => $request->title ?? $feature->title,
            'link' => $request->link ?? $feature->link,
            'desc' => $request->desc ?? $feature->desc,
            'type' => $request->type ?? 'home'
        ]);

        if ($request->featured) {
            $feature->medias()->sync($request->featured);
        }

        return redirect($request->session()->get('prev_route'))->with('message', 'Successfully updated.');
    }

    public function destroy($id)
    {
        $feature = MainFeature::findOrFail($id);

        foreach ($feature->medias as $media) {
            if (Storage::exists($media->url)) {
                Storage::delete($media->url);
            }

            if (Storage::exists('public/thumbnail/' . $media->slug)) {
                Storage::delete('public/thumbnail/' . $media->slug);
            }

            $media->delete();
        }

        $feature->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Successfully deleted.');
    }

    public function toggle($id)
    {
        $feature = MainFeature::findOrFail($id);

        $feature->update(['disabled' => !$feature->disabled]);

        return redirect(request()->session()->get('prev_route'))->with('message', 'Successfully Done.');
    }
}
