<?php

use App\Http\Controllers\MenuController;
use App\Livewire\Dashboard;
use App\Livewire\Menu\Index;
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

Route::view('/', 'welcome')->name('welcome');

// --- Rute untuk Dashboard Admin ---
// Ini akan menangani domain utama: restaurant.ngopikode.com
Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Menu Management
    Route::get('/menu', Index::class)->name('menu.index');

    // Order Management
    Route::get('/orders', \App\Livewire\Orders\Index::class)->name('orders.index');

    // Settings
    Route::get('/settings', \App\Livewire\Settings\Index::class)->name('settings.index');

    // Profile
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__ . '/auth.php';
