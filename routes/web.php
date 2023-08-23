<?php

use App\Http\Controllers\Api\Accounting\AccountAdjustmentController;
use App\Http\Controllers\Api\Accounting\AccountController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\Invoice\PurchaseInvoiceController;
use App\Http\Controllers\Api\Invoice\SaleInvoiceController;
use App\Http\Controllers\Api\Party\CustomerController;
use App\Http\Controllers\Api\Party\SupplierController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\Product\BrandController;
use App\Http\Controllers\Api\Product\ProductCategoryController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Product\UnitController;
use App\Http\Controllers\Api\Setting\CurrencyController;
use App\Http\Controllers\Api\Setting\TaxController;
use App\Http\Controllers\Api\Setting\WarehouseController;
use App\Http\Controllers\Api\Upload\UploadController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Database\Seeders\DevDemo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // echo 'hello welcome to invextry';
    return redirect('login');
})->name('home');

// create demo site
Route::get('/demo', function () {
    $seeder = new DevDemo();
    $seeder->run();

    return redirect('admin');
});

// All Auth Related Route
Route::get('/register', [RegisterController::class, 'registrationForm'])->name('registrationForm');
Route::post('/register', [RegisterController::class, 'register'])->name('registerUser')->middleware('viewonly');
Route::get('/login', [LoginController::class, 'loginForm'])->name('loginForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Handling All Admin Routes
Route::get('/admin/{any?}', function () {
    if (Auth::check()) {
        return view('admin.app');
    }

    return redirect('login');
})->where('any', '.*')->name('admin');

// All route available only for authenticated users
Route::group(['middleware' => ['auth']], function () {

    // User
    Route::get('/api/users/authenticated-user', [UserController::class, 'getAuthenticatedUser']);

    // Dashboard Overview
    Route::get('/api/dashboard-report', [DashboardController::class, 'getDashboardOverview']);

    // Route to handle media upload
    Route::post('/api/upload', [UploadController::class, 'upload']);
    Route::delete('/api/upload/{id}', [UploadController::class, 'delete']);

    // Currency
    Route::get('/api/currencies', [CurrencyController::class, 'index']);
    Route::get('/api/currencies/{id}', [CurrencyController::class, 'show']);
    Route::post('/api/currencies', [CurrencyController::class, 'store']);
    Route::put('/api/currencies/{id}', [CurrencyController::class, 'update']);
    Route::delete('/api/currencies/{id}', [CurrencyController::class, 'delete']);

    // Unit
    Route::get('/api/units', [UnitController::class, 'index']);
    Route::get('/api/base-units', [UnitController::class, 'getBaseUnits']);
    Route::get('/api/homogeneous-units/{id}', [UnitController::class, 'getHomogeneousUnits']);
    Route::get('/api/units/{id}', [UnitController::class, 'show']);
    Route::post('/api/units', [UnitController::class, 'store']);
    Route::put('/api/units/{id}', [UnitController::class, 'update']);
    Route::delete('/api/units/{id}', [UnitController::class, 'delete']);

    // Tax
    Route::get('/api/taxes', [TaxController::class, 'index']);
    Route::get('/api/taxes/{id}', [TaxController::class, 'show']);
    Route::post('/api/taxes', [TaxController::class, 'store']);
    Route::put('/api/taxes/{id}', [TaxController::class, 'update']);
    Route::delete('/api/taxes/{id}', [TaxController::class, 'delete']);

    // Warehouse
    Route::get('/api/warehouses', [WarehouseController::class, 'index']);
    Route::get('/api/warehouses/{id}', [WarehouseController::class, 'show']);
    Route::post('/api/warehouses', [WarehouseController::class, 'store']);
    Route::put('/api/warehouses/{id}', [WarehouseController::class, 'update']);
    Route::delete('/api/warehouses/{id}', [WarehouseController::class, 'delete']);

    // Brand
    Route::get('/api/brands', [BrandController::class, 'index']);
    Route::get('/api/brands/{id}', [BrandController::class, 'show']);
    Route::post('/api/brands', [BrandController::class, 'store']);
    Route::put('/api/brands/{id}', [BrandController::class, 'update']);
    Route::delete('/api/brands/{id}', [BrandController::class, 'delete']);

    // Product Categorues
    Route::get('/api/product-categories', [ProductCategoryController::class, 'index']);
    Route::get('/api/product-categories/{id}', [ProductCategoryController::class, 'show']);
    Route::post('/api/product-categories', [ProductCategoryController::class, 'store']);
    Route::put('/api/product-categories/{id}', [ProductCategoryController::class, 'update']);
    Route::delete('/api/product-categories/{id}', [ProductCategoryController::class, 'delete']);

    // Product
    Route::get('/api/products', [ProductController::class, 'index']);
    Route::get('/api/warehouse-products/{warehouse_id}/{name}', [ProductController::class, 'getProductsByNameAndWareHouse']);
    Route::get('/api/products-by-name/{name}', [ProductController::class, 'getProductsByName']);
    Route::get('/api/products/{id}', [ProductController::class, 'show']);
    Route::post('/api/products', [ProductController::class, 'store']);
    Route::put('/api/products/{id}', [ProductController::class, 'update']);
    Route::delete('/api/products/{id}', [ProductController::class, 'delete']);

    // Customers
    Route::get('/api/customers', [CustomerController::class, 'index']);
    Route::get('/api/customers/search/{search}', [CustomerController::class, 'search']);
    Route::get('/api/customers/{id}', [CustomerController::class, 'show']);
    Route::post('/api/customers', [CustomerController::class, 'store']);
    Route::put('/api/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/api/customers/{id}', [CustomerController::class, 'delete']);

    // suppliers
    Route::get('/api/suppliers', [SupplierController::class, 'index']);
    Route::get('/api/suppliers/search/{search}', [SupplierController::class, 'search']);
    Route::get('/api/suppliers/{id}', [SupplierController::class, 'show']);
    Route::post('/api/suppliers', [SupplierController::class, 'store']);
    Route::put('/api/suppliers/{id}', [SupplierController::class, 'update']);
    Route::delete('/api/suppliers/{id}', [SupplierController::class, 'delete']);

    // purchase
    Route::get('/api/purchases', [PurchaseInvoiceController::class, 'index']);
    Route::post('/api/purchases', [PurchaseInvoiceController::class, 'store']);

    // sale
    Route::get('/api/sales', [SaleInvoiceController::class, 'index']);
    Route::post('/api/sales', [SaleInvoiceController::class, 'store']);

    // Account
    Route::get('/api/accounts', [AccountController::class, 'index']);
    Route::get('/api/accounts/list', [AccountController::class, 'list']);
    Route::get('/api/accounts/{id}', [AccountController::class, 'show']);
    Route::post('/api/accounts', [AccountController::class, 'store']);
    Route::put('/api/accounts/{id}', [AccountController::class, 'update']);
    Route::delete('/api/accounts/{id}', [AccountController::class, 'delete']);

    // Account Balance Adjustment
    Route::get('/api/account-adjustments', [AccountAdjustmentController::class, 'index']);
    Route::get('/api/account-adjustments/{id}', [AccountAdjustmentController::class, 'show']);
    Route::post('/api/account-adjustments', [AccountAdjustmentController::class, 'store']);

    // Payment
    Route::post('/api/payments', [PaymentController::class, 'store']);

});
