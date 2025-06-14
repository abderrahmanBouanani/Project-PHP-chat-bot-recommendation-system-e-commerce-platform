<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CompteurController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin Dashboard API Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard/chart-data', [AdminDashboardController::class, 'chartData']);
    Route::get('/dashboard/recent-orders', [AdminDashboardController::class, 'recentOrders']);
});

// Cart Routes
Route::post('/cart/add', [CartController::class, 'addToCart']);

// Coupon Routes
Route::post('/coupon/apply', [CouponController::class, 'applyCoupon']);


// Compteur Routes
Route::post('/compteurs/track', [CompteurController::class, 'trackClick']);

// Admin Products API Routes
Route::prefix('admin')->group(function () {
    Route::get('/produit/search', [AdminProductController::class, 'search']);
    Route::get('/produit/categories', [AdminProductController::class, 'getCategories']);
});

// User API Routes
Route::post('/admin/users/{user}/toggle-block', [UserController::class, 'toggleBlock']);



