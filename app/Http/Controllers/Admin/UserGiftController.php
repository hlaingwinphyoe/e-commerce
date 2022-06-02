<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Notifications\GiftRequestedToAdmin;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

use App\Models\Gift;
use App\Models\UserGift;
use App\Models\Status;
use App\Models\User;

class UserGiftController extends Controller
{
    public function index()
    {
        $gifts = Gift::orderBy('stock')->orderBy('points')->get();

        return view('admin.user-gifts.index')->with([
            'gifts' => $gifts
        ]);
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {


            $status = Status::where('slug', 'pending')->first();

            $out_status = Status::where('slug', 'out')->first();

            $gift = Gift::findOrFail($request->gift);

            $user_gift = $gift->userGifts()->create([
                'user_id' => auth()->user()->id,
                'status_id' => $status->id
            ]);

            $gift->update(['stock' => $gift->stock - 1]);

            auth()->user()->userpoints()->create([
                'points' => $gift->points,
                'status_id' => $out_status->id,
                'data' => 'Get Gift'
            ]);

            //send notification to admin
            $users = User::havePermissions(['access-gift-noti'])->get();
            Notification::send($users, new GiftRequestedToAdmin($user_gift));
        });

        return redirect()->back()->with('message', 'Request has been send suceessfully.');
    }

    public function showGift()
    {
        $gifts = UserGift::filterOn()->where('user_id', auth()->user()->id)->latest()->paginate(20);

        return view('admin.user-gifts.show')->with([
            'gifts' => $gifts
        ]);
    }

    public function update(Request $request, $id)
    {
        DB::transaction(function () use($id){
            $user_gift = UserGift::findOrfail($id);

            $status = Status::where('slug', 'cancel')->first();

            $in_status = Status::where('slug', 'in')->first();

            $user_gift->update(['status_id' => $status->id]);

            $user_gift->gift->update(['stock' => $user_gift->gift->stock + 1]);

            auth()->user()->userpoints()->create([
                'points' => $user_gift->gift->points,
                'status_id' => $in_status->id,
                'data' => 'Cancel Gift'
            ]);

            //Send Noti to Admin when user cancelled requested gift
            // $users = User::havePermissions(['access-gift-noti'])->get();
            // Notification::send($users, new GiftRequestedToAdmin($user_gift));
        });

        return redirect($request->session()->get('prev_route'))->with('message', 'Cancel Gift successfully.');
    }

    public function destroy($id)
    {
        DB::transaction(function() use($id){
            $user_gift = UserGift::findOrFail($id);

            $in_status = Status::where('slug', 'in')->first();

            $user_gift->gift->update(['stock' => $user_gift->gift->stock + 1]);

            auth()->user()->userpoints()->create([
                'points' => $user_gift->gift->points,
                'status_id' => $in_status->id,
                'data' => 'Cancel Gift'
            ]);

            $user_gift->delete();
        });

        return redirect(request()->session()->get('prev_route'))->with('message', 'Cancel Gift successfully.');
    }
}
