<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdherentController;
use App\Http\Controllers\PersonneController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GroupeController;

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
    Route::resource('groupe', GroupeController::class);
    Route::resource('personne', PersonneController::class);

    Route::get('groupe/{id}/pdf', [GroupeController::class, 'pdf'])->name('groupe.pdf');
    Route::get('groupes/pdf', [GroupeController::class, 'groupes_pdf'])->name('groupes.pdf');

    Route::post('/import-adherents', [AdherentController::class, 'import'])->name('import-adherents');
    Route::post('/scan-drive-adherents', [AdherentController::class, 'scanDrive'])->name('scan-drive-adherents');
    Route::get('/facture/{id}', [AdherentController::class, 'generateFacture']);
});