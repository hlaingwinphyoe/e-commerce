<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gift;

class GiftController extends Controller
{
    public function index()
    {
        $gifts = Gift::filterOn()->latest()->paginate(20);

        return view('admin.gift.index', [
            'gifts' => $gifts,
        ]);
    }

    public function create()
    {
        return view('admin.gift.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'points' => 'required',
        ]);

        $gift = Gift::create([
            'name' => $request->name,
            'points' => $request->points,
        ]);

        $gift->medias()->sync($request->featured);

        return redirect()->route('admin.gifts.index')->with('success', 'Gift created successfully !');
    }

    public function edit($id)
    {
        $gift = Gift::findOrFail($id);

        return view('admin.gift.edit', [
            'gift' => $gift,
        ]);
    }

    public function update(Request $request, $id)
    {
        $gift = Gift::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'points' => 'required',
        ]);

        $gift->update([
            'name' => $request->name,
            'points' => $request->points,
        ]);

        $gift->medias()->sync($request->featured);

        return redirect()->route('admin.gifts.index')->with('success', 'Gift updated successfully !');
    }

    public function show($id)
    {
        $gift = Gift::findOrFail($id);

        return view('admin.gift.show')->with([
            'gift' => $gift
        ]);
    }

    public function destroy($id)
    {
        $gift = Gift::findOrFail($id);

        if($gift->inventories->count() || $gift->userGifts->count()) {
            return redirect()->back()->with('error', 'Cannot delete!');
        }

        $gift->delete();

        return redirect()->back()->with('success', 'Gift deleted successfully');
    }
}
