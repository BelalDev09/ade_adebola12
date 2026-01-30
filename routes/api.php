<?php

use App\Http\Controllers\API\NotifyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ContactUSController;
// use App\Http\Controllers\API\CmsController;
use App\Http\Controllers\API\RentController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\LocationController;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->middleware('throttle:3,10'); // 3 request / 10 min
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
});

// reviews

Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/profile-update', [ProfileController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/profile', [AuthController::class, 'profile']);
    // cms page
    Route::post('rent', [RentController::class, 'store']);
    // sale estimate
    Route::post('sale', [SaleController::class, 'store']);
    // search & review & report

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::post('/reviews/{review}/like-toggle', [ReviewController::class, 'toggleLike']);

    // Locations
    Route::get('/locations/search', [LocationController::class, 'search']);
    Route::post('/location/create', [LocationController::class, 'store']);
    // Reports
    Route::post('/reports', [ReportController::class, 'store']);
    Route::post('reviews/reply', [ReportController::class, 'reply']);

    // contact us route
    Route::post('contact', [ContactUSController::class, 'store']);

    // notification
    Route::get('v1/notifications', [NotifyController::class, 'index']);
    Route::post('v1/notifications/read/{id}', [NotifyController::class, 'markAsRead']);
    Route::post('v1/notifications/read-all', [NotifyController::class, 'markAllAsRead']);
});
