<?php

use App\Http\Controllers\Admin\BarcodeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SkuController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'prev_route'])->as('admin.')->group(function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    //switch lang
    Route::get('/langs/{lang}', [LanguageController::class, 'switchLang'])->name('langs.switch');
    //user profile
    Route::resource('/profiles', UserProfileController::class);
    Route::patch('/profiles/upload/{id}', [UserProfileController::class, 'upload'])->name('profiles.upload');
    Route::patch('/profiles/changepassword/{id}', [UserProfileController::class, 'changePassword'])->name('profiles.changepassword');

    //create sessions
    Route::resource('/items', ItemController::class);
    Route::delete('/item-delete/{item}', [ItemController::class, 'delete'])->name('items.delete');
    Route::patch('/item-restore/{item}', [ItemController::class, 'restore'])->name('items.restore');
    Route::resource('/skus', SkuController::class);

    Route::resource('/types', TypeController::class);
    Route::patch('/type-priority/{type}', [TypeController::class, 'changePriority'])->name('types.change-priority');
    
    Route::resource('/units', UnitController::class);

    Route::resource('/brands', BrandController::class);
    Route::resource('/suppliers', SupplierController::class);

    Route::resource('/customers', CustomerController::class);

    Route::resource('/users', UserController::class);
    Route::patch('/change-user-password/{user}', [UserController::class, 'changePassword'])->name('users.change-password');
    Route::post('/update-user-role/{user}', [UserController::class, 'updateRole'])->name('update-user-role');
    Route::post('/users-import', [UserController::class, 'import'])->name('users.import');
    Route::get('/users-import', function () {
        return view('admin.test.user-import');
    });

    Route::resource('/roles', RoleController::class);

    //barcode
    Route::get('/print-barcodes/{sku}', [BarcodeController::class, 'show']);
});