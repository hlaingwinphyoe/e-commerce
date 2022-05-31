<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Sku;
use App\Models\Waste;
use App\Models\Gift;
use App\Models\Status;

class SkuWasteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amt' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {

            $status = Status::where('slug', 'adjust')->first();

            $sku = Sku::find($request->sku_id);

            if ($request->status_id == $status->id) {

                if ($sku->getTotalWasteCount('!adjust') >= $request->amt) {
                    $waste = Waste::create([
                        'amt' => $request->amt,
                        'status_id' => $request->status_id,
                        'date' => $request->date ?? now(),
                        'remark' => $request->remark,
                    ]);
                    $sku->wastes()->attach($waste->id);

                    $sku->update(['stock' => $sku->stock + $request->amt]);

                } else {
                    return redirect()->back()->with('error', 'Your amount is greater than waste.');
                }
            } elseif ($sku->stock >= $request->amt) {

                $waste = Waste::create([
                    'amt' => $request->amt,
                    'status_id' => $request->status_id,
                    'date' => $request->date ?? now(),
                    'remark' => $request->remark,
                ]);

                $sku->wastes()->attach($waste->id);

                $sku->update(['stock' => $sku->stock - $request->amt]);
            } else {
                return redirect()->back()->with('error', 'No Stock.');
            }
        });
        return redirect($request->session()->get('prev_route'))->with('message', 'Add Waste');
    }

    public function addGift(Request $request)
    {
        $request->validate([
            'qty' => 'required|numeric',
            'points' => 'required|numeric'
        ]);

        DB::transaction(function () use ($request) {

            $sku = Sku::find($request->sku_id);

            if ($sku->stock >= $request->qty) {

                if ($sku->gift_inventories()->count()) {
                    $gift = $sku->gift_inventories()->first()->gift;
                } else {
                    $name = $sku->item->name;
                    $name .= $sku->data ? '(' . $sku->data . ')' : '';
                    $gift = Gift::create([
                        'name' => $name,
                        'points' => $request->points ?? 100
                    ]);
                    $gift->medias()->attach($sku->medias()->pluck('id'));
                }
                $sku->gift_inventories()->create([
                    'gift_id' => $gift->id,
                    'user_id' => auth()->user()->id,
                    'qty' => $request->qty,
                    'date' => $request->date,
                    'is_published' => 1
                ]);

                $gift->update(['stock' => $gift->stock + $request->qty]);

                $sku->update(['stock' => $sku->stock - $request->qty]);
            } else {
                return redirect()->back()->with('error', 'No Stock.');
            }
        });
        return redirect($request->session()->get('prev_route'))->with('message', 'Add Gift');
    }
}
