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
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\DiscountypeController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\TownshipController;
use App\Http\Controllers\Admin\DeliFeeController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MainFeatureController;

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\SkuWasteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'prev_route'])->as('admin.')->group(function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/slides', SlideController::class);
    Route::resource('/mainfeatures', MainFeatureController::class);
    Route::get('/mainfeatures-toggle/{mainfeature}', [MainFeatureController::class, 'toggle'])->name('mainfeatures.toggle');
    Route::resource('/faqs', FaqController::class);

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
    Route::resource('/inventories', InventoryController::class);

    //sku-history
    Route::get('/skus', [SkuController::class, 'index'])->name('skus.index');
    Route::get('/skus/{sku}', [SkuController::class, 'show'])->name('skus.show');
    Route::patch('/skus/{sku}', [SkuController::class, 'update'])->name('skus.update');
    Route::delete('/skus/{sku}', [SkuController::class, 'destroy'])->name('skus.destroy');
    Route::post('/add-stock/{sku}', [SkuController::class, 'addStock'])->name('skus.add-stock');
    Route::get('/reset-stock/{sku}', [SkuController::class, 'resetStock'])->name('skus.reset-stock');

    //wastes
    Route::post('/sku-wastes', [SkuWasteController::class, 'store'])->name('sku-wastes.store');
    Route::post('/sku-gifts', [SkuWasteController::class, 'addGift'])->name('sku-gifts.store');

    //return 
    Route::resource('/returns', ReturnController::class);

    Route::resource('/inventories', InventoryController::class);

    //discount
    Route::resource('/discountypes', DiscountypeController::class);

    //addresses
    Route::resource('/regions', RegionController::class);
    Route::resource('/countries', CountryController::class);
    Route::resource('/townships', TownshipController::class);
    Route::resource('/delifees', DeliFeeController::class);
    Route::resource('/deliveries', DeliveryController::class);

    //user control
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

    //order
    Route::resource('/orders', OrderController::class);
    Route::post('/update-order-delivery/{order}', [OrderController::class, 'updateDelivery'])->name('update-order-delivery');
    Route::resource('/pos', POSController::class);
    Route::get('/pos-print/{pos}', [POSController::class, 'print'])->name('pos.print');
    
   

    //sales
    Route::resource('/sales', SaleController::class);
    Route::get('/sales-print/{sale}', [SaleController::class, 'print'])->name('sales.print');
    Route::get('/sales-excel', [SaleController::class, 'excel'])->name('sales.excel');
});