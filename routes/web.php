<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

 Route::get('/products', [ProductController::class, 'index'])
    ->name('products')
    ->middleware('auth');

Route::post('/products', [ProductController::class, 'store'])
    ->name('product.store')
    ->middleware('auth');

Route::put('/products/{id}', [ProductController::class, 'update'])
    ->name('product.update')
    ->middleware('auth');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])
    ->name('product.destroy')
    ->middleware('auth');

