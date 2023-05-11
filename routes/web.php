<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TraceabilityController;

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
    return view('layouts.auth.login');
})->middleware('guest');

// unauthencticated user
Route::middleware(['guest'])->group(function () {

    Route::get('/login', [LoginController::class, 'index'])->name('login.index');
    Route::post('/login-auth', [LoginController::class, 'authenticate'])->name('login.auth');
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/register-store', [RegisterController::class, 'store'])->name('register.store');
});

// authenticated user
Route::middleware(['auth'])->group(function () {

    // FG
    Route::get('trace/scan/antenna', [TraceabilityController::class, 'index'])->name('antenna.index');
    Route::get('trace/scan/antenna/storeKanban', [TraceabilityController::class, 'storeKanban'])->name('antenna.storeKanban');
    Route::get('trace/scan/antenna/storePart', [TraceabilityController::class, 'storePart'])->name('antenna.storePart');

    // NG
    Route::get('trace/scan/antenna/ng', [TraceabilityController::class, 'ng-index'])->name('antenna.ng.index');
});
