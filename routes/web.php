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

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout.auth');

    Route::get('/trace/part', [TraceabilityController::class, 'traceIndex'])->name('traceability.index');
    Route::get('/trace/part/{code}', [TraceabilityController::class, 'trace'])->name('traceability.trace');

    Route::prefix('trace/scan')->group(function () {
        // FG
        Route::get('antenna', [TraceabilityController::class, 'index'])->name('antenna.index');
        Route::get('antenna/storeKanban', [TraceabilityController::class, 'storeKanban'])->name('antenna.storeKanban');
        Route::get('antenna/storePart', [TraceabilityController::class, 'storePart'])->name('antenna.storePart');

        // NG
        Route::get('antenna/ng/{ngId}', [TraceabilityController::class, 'ngAntenna'])->name('antenna.ng.index');
        Route::get('antenna/ng/store/{ngId}/{part}', [TraceabilityController::class, 'storeNgAntenna'])->name('antenna.ng.store');

        // ng check
        Route::get('ng/check', [TraceabilityController::class, 'ngCheck'])->name('ng.check');
    });
});
