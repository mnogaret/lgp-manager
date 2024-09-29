<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdherentController;
use App\Http\Controllers\AdhesionController;
use App\Http\Controllers\BadgesController;
use App\Http\Controllers\FicheController;
use App\Http\Controllers\PersonneController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GroupeController;
use App\Http\Controllers\PassageDeLameController;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\SaisonController;
use App\Http\Controllers\SeanceController;

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
Route::get('/pointage/{code}', [PointageController::class, 'pointage'])->name('pointage');
Route::get('/fiche/{hash_code}', [FicheController::class, 'show']);
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('/welcome');
    })->name('welcome');
    Route::get('/stats', function () {
        return view('/stats');
    });
    Route::get('/admin', function () {
        return view('/admin');
    });

    // Verb          Path                        Action  Route Name
    // GET           /users                      index   users.index
    // GET           /users/create               create  users.create
    // POST          /users                      store   users.store
    // GET           /users/{user}               show    users.show
    // GET           /users/{user}/edit          edit    users.edit
    // PUT|PATCH     /users/{user}               update  users.update
    // DELETE        /users/{user}               destroy users.destroy
    Route::resource('adherent', AdherentController::class);
    Route::resource('groupe', GroupeController::class);

    Route::post('/saison/select', [SaisonController::class, 'select'])->name('saison.select');

    Route::get('/personne/{personne}/edit', [PersonneController::class, 'edit'])->name('personne.edit');
    Route::put('/personne/{personne}', [PersonneController::class, 'update'])->name('personne.update');

    Route::get('groupe/{id}/pdf', [GroupeController::class, 'pdf'])->name('groupe.pdf');
    Route::get('groupes/pdf', [GroupeController::class, 'groupes_pdf'])->name('groupes.pdf');
    Route::get('groupes/lames-pdf', [GroupeController::class, 'lames_pdf'])->name('groupes.lames-pdf');
    Route::get('passages/lames-pdf', [PassageDeLameController::class, 'lames_pdf'])->name('passages.lames-pdf');
    Route::get('badges/pdf', [BadgesController::class, 'pdf'])->name('badges.pdf');
    Route::get('badges/csv', [BadgesController::class, 'csv'])->name('badges.csv');
    Route::get('badges/zip', [BadgesController::class, 'zip'])->name('badges.zip');

    Route::get('/adhesion/{adhesion}/change-etat', [AdhesionController::class, 'changeEtat'])->name('adhesion.changeEtat');

    Route::post('/import-adherents-2023', [AdherentController::class, 'import2023'])->name('import-adherents-2023');
    Route::post('/import-adherents-2024', [AdherentController::class, 'import2024'])->name('import-adherents-2024');
    Route::post('/scan-drive-adherents', [AdherentController::class, 'scanDrive'])->name('scan-drive-adherents');
    Route::get('/facture/{id}', [AdherentController::class, 'generateFacture']);

    Route::post('/import-passages', [PassageDeLameController::class, 'import'])->name('import-passages');

    Route::get('/seance/create/{groupe}/{creneau}', [SeanceController::class, 'create'])->name('seance.create');
    Route::post('/seance/store/{groupe}/{creneau}', [SeanceController::class, 'store'])->name('seance.store');
});