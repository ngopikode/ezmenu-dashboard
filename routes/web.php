<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Rute untuk Rich Preview ---
// Ini akan menangani domain seperti: samarotikukus.ngopikode.my.id/menu/1
Route::domain('{subdomain}.' . config('app.frontend_url_base'))->group(function () {
    Route::get('/menu/{productId}', [MenuController::class, 'showProductPreview'])
        ->where('productId', '[0-9]+')
        ->name('product.preview');
});


// --- Rute untuk Dashboard Admin ---
// Ini akan menangani domain utama: restaurant.ngopikode.com
Route::middleware(['auth'])->group(function () {

    Route::middleware(['verified'])->group(function () {
        Route::view('/', 'dashboard')->name('dashboard');
    });
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__ . '/auth.php';
