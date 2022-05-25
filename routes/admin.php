<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SkuController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserProfileController;
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
    Route::resource('/units', UnitController::class);
    
});