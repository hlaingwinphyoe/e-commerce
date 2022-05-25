<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    public function getIcons()
    {
        $files = File::files(public_path('images/icons'));
        $new_images = [];

        foreach ($files as $file) {
            $new_images[] = $file->getRelativePathname();
        }

        return response()->json($new_images);
    }

    public function show($id)
    {
        $media = Media::findOrFail($id);

        return response()->json($media);
    }

    public function store(Request $request)
    {
        $max_size = env('MAX_FILE_SIZE', 102400);

        $request->validate([
            'media' => 'required|mimes:jpg,jpeg,png,gif,webp,bmp,mp3,ogg,wav|max:' . $max_size,
        ]);

        $media = DB::transaction(function () use ($request) {

            $file = $request->file('media');
            $fileNameWithExt = $file->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $fileNameToStore = $fileName . '_' . time() . '.' . $file->extension();

            $url = $file->storeAs('public/', $fileNameToStore);

            $webpFileName = $fileName . '_' . time() . '.webp';

            $types = ['slides', 'home-features'];

            $can_crop = !in_array($request->type, $types);

            Storage::move($url, 'public/thumbnail/' . $fileNameToStore); //tmp

            if (substr($file->getMimeType(), 0, 5) == 'image' && $can_crop) {

                $thumbnailPath = public_path('storage/thumbnail/' . $webpFileName);

                $tmpPath = public_path('storage/thumbnail/' . $fileNameToStore);

                //pixels (400x600)
                $img = Image::make($tmpPath)->encode('webp', 90)->resize(400, 600, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailPath);
                Storage::delete('public/thumbnail/' . $fileNameToStore);
            }

            $media = Media::create([
                'name' => $fileName,
                'slug' => substr($file->getMimeType(), 0, 5) == 'image' && $can_crop ? $webpFileName : $fileNameToStore,
                'url' => $url,
                'ext' => $file->extension(),
                'type' => $request->type,
            ]);

            return $media;
        });

        return response()->json($media);
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        $media->update([
            'title' => $request->title,
            'priority' => $request->priority
        ]);

        return response()->json($media);
    }

    public function destroy(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        if (Storage::exists($media->url)) {
            Storage::delete($media->url);
        }

        if (Storage::exists('public/thumbnail/' . $media->slug)) {
            Storage::delete('public/thumbnail/' . $media->slug);
        }

        $media->delete();

        // return redirect()->back();
        return response()->json($media);
    }

    public function getMedias()
    {
        $medias = Media::latest()->paginate(16);

        return response()->json($medias);
    }

    public function check(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $medias = Media::whereIn('id', $request->images)->update([
            'is_check' => false,
        ]);

        $media->update([
            'is_check' => true
        ]);

        return response()->json($media);
    }
}
