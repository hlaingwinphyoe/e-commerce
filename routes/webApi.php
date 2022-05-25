<?php

//media

use App\Http\Controllers\WebApi\MediaController;
use Illuminate\Support\Facades\Route;

Route::resource('/medias', MediaController::class);
Route::patch('/medias/check/{id}', [MediaController::class, 'check']);
Route::get('/get-icons', [MediaController::class, 'getIcons']);
