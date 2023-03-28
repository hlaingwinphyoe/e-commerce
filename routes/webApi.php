<?php

//media

use App\Http\Controllers\WebApi\AttributeController;
use App\Http\Controllers\WebApi\InventoryController;
use App\Http\Controllers\WebApi\ItemAttributeController;
use App\Http\Controllers\WebApi\ItemDiscountController;
use App\Http\Controllers\WebApi\ItemPricingController;
use App\Http\Controllers\WebApi\ItemSkuController;
use App\Http\Controllers\WebApi\MediaController;
use App\Http\Controllers\WebApi\PricingController;
use App\Http\Controllers\WebApi\ReturnController;
use App\Http\Controllers\WebApi\ReturnSkuController;
use App\Http\Controllers\WebApi\SingleSkuController;
use App\Http\Controllers\WebApi\SkuBarcodeController;
use App\Http\Controllers\WebApi\SkuController;
use App\Http\Controllers\WebApi\SkuInventoryController;
use App\Http\Controllers\WebApi\SkuMediaController;
use App\Http\Controllers\WebApi\SkuPricingController;
use App\Http\Controllers\WebApi\ValueController;
use App\Http\Controllers\WebApi\TypeController;
use App\Http\Controllers\WebApi\VariantController;
use App\Http\Controllers\WebApi\StatusController;
use App\Http\Controllers\WebApi\OrderController;
use App\Http\Controllers\WebApi\TransactionController;
use App\Http\Controllers\WebApi\CurrencyController;
use App\Http\Controllers\WebApi\UserController;
use App\Http\Controllers\WebApi\OrderSkuController;
use App\Http\Controllers\WebApi\CostController;
use App\Http\Controllers\WebApi\ExpenseController;
use App\Http\Controllers\WebApi\GiftController;
use App\Http\Controllers\WebApi\GiftInventoryController;
use App\Http\Controllers\WebApi\OrderNotificationController;
use App\Http\Controllers\WebApi\NotificationController;
use App\Http\Controllers\WebApi\V1\ItemController as V1ItemController;
use App\Http\Controllers\WebApi\V1\ItemDiscountController as V1ItemDiscountController;
use App\Http\Controllers\WebApi\V1\ItemPricingController as V1ItemPricingController;
use App\Http\Controllers\WebApi\WasteController;
use App\Http\Controllers\WebApi\GeneralController;
use App\Http\Controllers\WebApi\GeneralInventoryController;
use App\Http\Controllers\WebApi\ReportController;
use App\Http\Controllers\WebApi\WebPushNotiController;

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

Route::get('/skus', [SkuController::class, 'index']);
Route::post('/skus', [SkuController::class, 'store']);
Route::delete('/skus/{sku}', [SkuController::class, 'destroy']);
Route::get('/sku-attributes/{sku}', [SkuController::class, 'getAttributes']);
Route::get('/sku-variants/{sku}', [SkuController::class, 'getVariants']);
Route::get('/make-sku/{item}', [SkuController::class, 'makeSku']);
Route::get('/popular-skus', [SkuController::class, 'getPopular']);

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

//barcodes
Route::patch('/sku-barcodes/{sku}', [SkuBarcodeController::class, 'update']);

//inventory
Route::get('/inventories/create', [InventoryController::class, 'create']);
Route::patch('/inventories/{inventory}', [InventoryController::class, 'update']);



//sku-inventory
Route::post('/sku-inventories/{inventory}', [SkuInventoryController::class, 'store']);
Route::delete('/sku-inventories/{inventory}/{sku}', [SkuInventoryController::class, 'destroy']);

// general
Route::post('/general-skus/{sku}', [GeneralController::class, 'store']);
Route::patch('/generals/{id}',[GeneralController::class,'update']);

//returns
Route::patch('/returns/{return}', [ReturnController::class, 'update']);
Route::post('/return-skus/{return}', [ReturnSkuController::class, 'store']);
Route::delete('/return-skus/{return}/{sku}', [ReturnSkuController::class, 'destroy']);

//pos
Route::get('/statuses', [StatusController::class, 'index']);

//orders
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/get-payment-params/{order}', [OrderController::class, 'getPaymentParams']);
Route::patch('/orders/{order}', [OrderController::class, 'update']);
Route::get('/get-balance/{order}', [OrderController::class, 'getBalance']);
Route::get('/get-orders', [OrderController::class, 'getOrders']);
Route::patch('/cancel-orders/{order}', [OrderController::class, 'cancelOrder']);
Route::patch('/save-customer/{order}', [OrderController::class, 'saveCustomer']);
Route::post('/order-discount/{order}', [OrderController::class, 'addDiscount']);
Route::patch('/order-deli-fee/{order}', [OrderController::class, 'addDeliFee']);
Route::post('/orders-confirm/{order}', [OrderController::class, 'confirm']);

//transactions
Route::resource('/transactions', TransactionController::class);

//resource routes
Route::resource('/types', TypeController::class);
Route::get('/type-skus/{type}', [TypeController::class, 'getSkus']);

//order-sku
Route::post('/order-skus/{order}', [OrderSkuController::class, 'store']);
Route::patch('/order-skus/{order}/{sku}', [OrderSkuController::class, 'update']);
Route::delete('/order-skus/{order}/{sku}', [OrderSkuController::class, 'destroy']);

// cost
Route::resource('/costs', CostController::class);

// waste
Route::resource('/wastes', WasteController::class);

// pricing
Route::resource('/pricings', PricingController::class);

//currencies
Route::get('/currencies', [CurrencyController::class, 'index']);

//users
Route::get('/users', [UserController::class, 'index']);

//gifts
Route::get('/gifts', [GiftController::class, 'index']);
Route::post('/gift-inventories', [GiftInventoryController::class, 'store']);
Route::patch('/gift-inventories/{inventory}', [GiftInventoryController::class, 'update']);
Route::patch('/gift-inventories-close/{inventory}', [GiftInventoryController::class, 'close']);

//noti
Route::get('/get-order-noti', [OrderNotificationController::class, 'getOrderNoti']);
Route::patch('/mark-as-read/{notification}', [NotificationController::class, 'markAsRead']);

// Route::post('/push-noti.js',[WebPushNotiController::class ,'store']);

Route::prefix('v1')->group(function () {
    //item-discounts
    Route::get('/item-discounts/{item}', [V1ItemDiscountController::class, 'index']);
    Route::post('/item-discounts/{item}', [V1ItemDiscountController::class, 'store']);
    Route::patch('/item-discounts/{discount}', [V1ItemDiscountController::class, 'update']);
    Route::delete('/item-discounts/{discount}', [V1ItemDiscountController::class, 'destroy']);

    Route::patch('/items/{item}', [V1ItemController::class, 'update']);

    //item-pricings
    Route::get('/item-pricings/{item}', [V1ItemPricingController::class, 'index']);
    Route::post('/item-pricings/{item}', [V1ItemPricingController::class, 'store']); //use in new
});

Route::patch('/inventory-publish/{inventory}', [GeneralInventoryController::class, 'publish']);

Route::get('/expenses', [ExpenseController::class, 'index']);
Route::post('/expenses', [ExpenseController::class, 'store']);
Route::patch('/expenses/{expense}', [ExpenseController::class, 'update']);
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy']);

//reports
Route::get('/sales', [ReportController::class, 'sales']);
Route::get('/sales-data', [ReportController::class, 'getData']);
Route::get('/sales-category', [ReportController::class, 'category']);

Route::get('/month-periods', [ReportController::class, 'monthPeriod']);
