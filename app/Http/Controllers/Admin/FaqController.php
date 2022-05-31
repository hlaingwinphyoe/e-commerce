<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Faq;
use App\Models\FaqType;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:access-faq')->only(['index', 'show']);
        $this->middleware('permissions:create-faq')->only(['create', 'store']);
        $this->middleware('permissions:edit-faq')->only(['edit', 'update']);
        $this->middleware('permissions:delete-faq')->only('destroy');
    }

    public function index()
    {
        $faqs = Faq::filterOn()->latest()->paginate(20);

        $faq_types = FaqType::get();

        return view('admin.faqs.index', compact('faqs', 'faq_types'));
    }

    public function create()
    {
        $faq_types = FaqType::get();

        return view('admin.faqs.create')->with([
            'faq_types' => $faq_types
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'faq_type' => 'required'
        ]);


        $faq = Faq::create([
            'title' => $request->title,
            'desc' => $request->description,
            'faq_type_id' => $request->faq_type,
        ]);

        return redirect()->route('admin.faqs.index')->with('message', 'Faq Created.');
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);

        $faq_types = FaqType::get();

        return view('admin.faqs.edit')->with([
            'faq' => $faq,
            'faq_types' => $faq_types
        ]);
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'faq_type' => 'required'
        ]);

        $faq->update([
            'title' => $request->title,
            'desc' => $request->description,
            'faq_type_id' => $request->faq_type,
        ]);


        return redirect($request->session()->get('prev_route'))->with('message', 'Faq Updated.');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);

        $faq->delete();

        return redirect(request()->session()->get('prev_route'))->with('message', 'Deleted successfully!');
    }
}
