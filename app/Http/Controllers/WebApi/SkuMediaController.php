<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\Sku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SkuMediaController extends Controller
{
    public function store(Request $request, $id)
    {
        $sku = Sku::findOrFail($id);

        $max_size = env('MAX_FILE_SIZE', 102400);

        $request->validate([
            'media' => 'required|mimes:jpg,jpeg,png,gif,webp,bmp,mp3,ogg,wav|max:' . $max_size . '',
        ]);

        $media = DB::transaction(function () use ($request, $sku) {

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
                Storage::delete($tmpPath);
            }

            $media = $sku->medias()->create([
                'name' => $fileName,
                'slug' => substr($file->getMimeType(), 0, 5) == 'image' && $can_crop ? $webpFileName : $fileNameToStore,
                'url' => $url,
                'ext' => $file->extension(),
                'type' => 'sku',
            ]);

            return $media;
        });
        return response()->json($media);
    }
}
