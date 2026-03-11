<?php

use App\Http\Controllers\MenuController;
use App\Livewire\Tenant\Dashboard\DashboardIndex;
use App\Livewire\Tenant\Menu\MenuManager;
use App\Livewire\Tenant\Order\OrderManager;
use App\Livewire\Tenant\Profile\UserProfile;
use App\Livewire\Tenant\Settings\RestaurantSettings;
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

Route::view('/', 'welcome')->name('welcome');

// --- Rute untuk Dashboard Admin dan Rich Preview ---
// Ini akan menangani domain seperti: subdomain.pakaiapp.online
Route::domain('{subdomain}.' . config('app.frontend_url_base'))
    ->middleware('validateSubdomain')
    ->group(function () {
        // --- Rute untuk Rich Preview ---
        // Ini akan menangani URL seperti: /menu/1
        Route::get('/menu/{productId}', [MenuController::class, 'showProductPreview'])->name('product.preview');

        // Halaman Preview Story (HTML)
        Route::get('/menu/{productId}/story', [MenuController::class, 'shareToStory'])->name('product.story');

        // Generate Gambar Story (JPEG)
        Route::get('/menu/{productId}/story/image', [MenuController::class, 'generateStoryImage'])->name('product.story.image');
    });

// --- Rute untuk Dashboard Admin ---
Route::prefix('dashboard')->group(function () {

    Route::middleware('auth:web')
        ->group(function () {
            // Dashboard
            Route::get('/', DashboardIndex::class)->name('dashboard');

            // Menu Management
            Route::get('menu', MenuManager::class)->name('menu.index');

            // Order Management
            Route::get('/orders', OrderManager::class)->name('orders.index');

            // Settings
            Route::get('/settings', RestaurantSettings::class)->name('settings.index');

            // Profile
            Route::get('/profile', UserProfile::class)->name('profile');

            // Include auth routes within the subdomain group
        });

    require __DIR__ . '/auth.php';
});
