<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;

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
