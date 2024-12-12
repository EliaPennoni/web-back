<?php

use App\Http\Controllers\PhotosController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\MainController as AdminMainController;

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

Route::get('/', [MainController::class, 'register'])->name('home');

Route::get('form', [MainController::class, 'form'])->name('form')->middleware('auth');

Route::post('store', [PhotosController::class, 'store'])->name('photos.store')->middleware('auth');

Route::get('index', [PhotosController::class, 'index'])->name('index')->middleware('auth');

Route::delete('/photos/{id}', [PhotosController::class, 'delete'])->name('photos.delete')->middleware('auth');

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');

    });

require __DIR__ . '/auth.php';
