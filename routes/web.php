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

// Route for generating rich social media previews for products
Route::get('/{subdomain}/menu/{productId}', [MenuController::class, 'showProductPreview'])
    ->where('productId', '[0-9]+'); // Ensure productId is a number

Route::middleware(['auth'])->group(function () {

    Route::middleware(['verified'])->group(function () {
        Route::view('/', 'dashboard')->name('dashboard');
    });
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__ . '/auth.php';
