<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdherentController;
use App\Http\Controllers\PersonneController;
use App\Http\Controllers\GoogleAuthController;

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

Route::get('/login', [GoogleAuthController::class, 'redirectToGoogle'])->name('login');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::get('/logout', [GoogleAuthController::class, 'logout']);
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('/welcome');
    });
    Route::get('/stats', function () {
        return view('/stats');
    });
    Route::get('/admin', function () {
        return view('/admin');
    });
    Route::resource('adherent', AdherentController::class);
    Route::resource('personne', PersonneController::class);

    Route::post('/import-adherents', [AdherentController::class, 'import'])->name('import-adherents');
    Route::post('/scan-drive-adherents', [AdherentController::class, 'scanDrive'])->name('scan-drive-adherents');
});