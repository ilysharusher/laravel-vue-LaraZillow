<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{AuthController, LoginController, LogoutController, RegisterController};
use App\Http\Controllers\{IndexController, ListingController, ListingImageController, RealtorListingController};

Route::get('/', [IndexController::class, 'index']);
Route::get('/hello', [IndexController::class, 'show'])
    ->name('hello')
    ->middleware('auth');

Route::resource('listing', ListingController::class)->only('index', 'show');

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'login'])->name('login');
        Route::post('login', LoginController::class)->name('login.store');
        Route::get('register', [AuthController::class, 'register'])->name('register');
        Route::post('register', RegisterController::class)->name('register.store');
    });
    Route::middleware('auth')->group(function () {
        Route::post('logout', LogoutController::class)->name('logout');
    });
});

Route::prefix('realtor')->middleware('auth')->name('realtor.')->group(function () {
    Route::patch('listing/{listing}/restore', [RealtorListingController::class, 'restore'])->name('listing.restore')->withTrashed();
    Route::resource('listing', RealtorListingController::class);
    Route::resource('listing.image', ListingImageController::class);
});
