<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;

// Public Routes
Route::get('/', fn() => view('home'))->name('home');

// Auth
Auth::routes(['register' => true, 'reset' => false]);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');
    Route::get('/collection', [CollectionController::class, 'index'])->name('collection');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('/location', [LocationController::class, 'index'])->name('location');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // Wishlist Routes
    Route::post('/wishlist/toggle/{figurineId}', [WishlistController::class, 'toggleWishlist'])->name('wishlist.toggle');

    // Collection Routes
    Route::post('/collection/add/{id}', [CollectionController::class, 'add']);
    Route::delete('/collection/remove/{id}', [CollectionController::class, 'remove'])->name('collection.remove');
    Route::post('/collection/add-full', [CollectionController::class, 'addFull']);
    Route::post('/collection/{id}/update', [CollectionController::class, 'update'])->name('collection.update');
    Route::post('/collection/{id}/delete-one', [CollectionController::class, 'deleteOne']);

    // Dashboard Charts
    Route::get('/dashboard/series-charts', [DashboardController::class, 'getSeriesCharts']);
});