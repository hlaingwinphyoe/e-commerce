<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Site\TypeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/category/{category?}/{type?}', [TypeController::class, 'category'])->name('category');
//save-invoice
Route::get('/save-invoice/{order}', function ($id) {
    $order = Order::find($id);

    if ($order) {
        $address = App\Models\Status::isType('address')->first();
        $phone = App\Models\Status::where('type', 'phone')->first();

        return view('components.invoice')->with([
            'order' => $order,
            'address' => $address,
            'phone' => $phone
        ]);
    }

    return redirect()->back();
})->name('save-invoice');