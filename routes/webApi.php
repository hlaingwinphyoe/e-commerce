<?php

//media

use App\Http\Controllers\WebApi\AttributeController;
use App\Http\Controllers\WebApi\ItemAttributeController;
use App\Http\Controllers\WebApi\ItemDiscountController;
use App\Http\Controllers\WebApi\ItemPricingController;
use App\Http\Controllers\WebApi\ItemSkuController;
use App\Http\Controllers\WebApi\MediaController;
use App\Http\Controllers\WebApi\SingleSkuController;
use App\Http\Controllers\WebApi\SkuController;
use App\Http\Controllers\WebApi\SkuMediaController;
use App\Http\Controllers\WebApi\SkuPricingController;
use App\Http\Controllers\WebApi\ValueController;
use App\Http\Controllers\WebApi\TypeController;
use App\Http\Controllers\WebApi\VariantController;
use Illuminate\Support\Facades\Route;

Route::resource('/medias', MediaController::class);
Route::patch('/medias/check/{id}', [MediaController::class, 'check']);
Route::get('/get-icons', [MediaController::class, 'getIcons']);
Route::get('/types', [TypeController::class, 'index']);
Route::post('/types/create', [TypeController::class, 'store']);

//skus
Route::get('/item-skus/{item}', [ItemSkuController::class, 'index']);
Route::post('/item-skus/{item}', [ItemSkuController::class, 'store']);

Route::post('/single-values', [ValueController::class, 'singleStore']);
Route::post('/values', [ValueController::class, 'store']);
Route::delete('/values/{value}', [ValueController::class, 'destroy']);

Route::post('/single-skus', [SingleSkuController::class, 'store']);
Route::delete('/single-skus/{item}', [SingleSkuController::class, 'destroy']);

Route::delete('/skus/{sku}', [SkuController::class, 'destroy']);
Route::get('/sku-attributes/{sku}', [SkuController::class, 'getAttributes']);
Route::get('/sku-variants/{sku}', [SkuController::class, 'getVariants']);
Route::get('/make-sku/{item}', [SkuController::class, 'makeSku']);

Route::post('/sku-medias/{sku}', [SkuMediaController::class, 'store']);

//variants
Route::delete('/variants/{variant}', [VariantController::class, 'destroy']);

//attributes
Route::get('/item-attributes/{item}', [ItemAttributeController::class, 'index']);
Route::post('/attributes', [AttributeController::class, 'store']);
Route::patch('/attributes/{attribute}', [AttributeController::class, 'update']);
Route::delete('/attributes/{attribute}', [AttributeController::class, 'destroy']);
Route::post('/attribute-values', [AttributeController::class, 'addAttributeValue']);

//sku-pricings
Route::patch('/sku-pricings/{pricing}', [SkuPricingController::class, 'update']);
Route::delete('/sku-pricings/{pricing}', [SkuPricingController::class, 'destroy']);

//item-pricings
Route::get('/item-pricings/{item}', [ItemPricingController::class, 'index']);
Route::post('/item-pricings/{item}', [ItemPricingController::class, 'store']);

//item-discounts
Route::post('/item-discounts', [ItemDiscountController::class, 'store']);
Route::patch('/item-discounts/{discount}', [ItemDiscountController::class, 'update']);
Route::delete('/item-discounts/{discount}', [ItemDiscountController::class, 'destroy']);