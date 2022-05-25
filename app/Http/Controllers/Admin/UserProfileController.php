<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('admin.profile.index')->with([
            'user' => $user
        ]);
    }

    public function edit($id)
    {
        if (auth()->user()->id != $id) {
            abort(403);
        }

        $user = auth()->user();

        return view('admin.profile.edit')->with([
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail(auth()->user()->id);

        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required|numeric|min:9'
        ]);

        $user->update($request->only(['name', 'email', 'phone']));

        return redirect()->back()->with('message', 'Updated!');
    }

    public function upload(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);

        $request->validate([
            'image' => 'required'
        ]);

        if ($user->medias()->count()) {
            foreach ($user->medias as $media) {
                Storage::delete($media->url);
                $media->delete();
            }
        }

        $file = $request->file('image');
        $fileNameWithExt = $file->getClientOriginalName();
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $fileNameToStore = $fileName . '_' . time() . '.' . $file->extension();

        $url = $file->storeAs('public', $fileNameToStore);

        $media = Media::create([
            'name' => $fileName,
            'slug' => $fileNameToStore,
            'url' => $url,
            'ext' => $file->extension(),
            'type' => 'profile',
        ]);

        Storage::move($media->url, 'public/thumbnail/' . $media->slug);

        $thumbnailPath = public_path('storage/thumbnail/' . $media->slug);

        $img = Image::make($thumbnailPath)->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save($thumbnailPath);

        $user->medias()->sync($media->id);

        return redirect()->back()->with('message', 'Profile successfully updated!');
    }

    public function changePassword(Request $request)
    {

        $user = User::findOrFail(auth()->user()->id);

        $request->validate([
            'password' => ['required', new MatchOldPassword],
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        Auth::logout();

        return redirect()->back()->with('message', 'Password successfully changed!');
    }
}
