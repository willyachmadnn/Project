<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PageController,
    AuthController,
    AgendaController,
    NotulenController,
    TamuController
};

//
// PUBLIC
//
Route::get('/', [PageController::class, 'index'])->middleware('cache.headers:1800')->name('landing');
Route::get('/api/agenda-counts', [PageController::class, 'getAgendaCounts'])->middleware('cache.headers:300')->name('api.agenda.counts');

// Auth (admin)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//
// PROTECTED (auth:admin)
//
Route::middleware('auth:admin')->group(function () {
    // Dashboard diarahkan ke daftar agenda
    Route::redirect('/dashboard', '/agenda')->name('dashboard');

    // TAMU (nested di Agenda)
    Route::prefix('agenda/{agenda}/tamu')->name('agenda.tamu.')->group(function () {
        Route::get('/', [TamuController::class, 'index'])->name('index');
        Route::post('/', [TamuController::class, 'store'])->name('store');
        Route::put('/{tamu:NIP}', [TamuController::class, 'update'])->name('update');
        Route::delete('/{tamu:NIP}', [TamuController::class, 'destroy'])->name('destroy');

        // Jika ada link create lama, arahkan ke index (form via modal)
        Route::get('/create', function (\App\Models\Agenda $agenda) {
            return redirect()->route('agenda.tamu.index', $agenda);
        })->name('create');
    });

    // NOTULEN (nested di Agenda)
    Route::prefix('agenda/{agenda}/notulen')->name('agenda.notulen.')->group(function () {
        Route::get('/create', [NotulenController::class, 'create'])->name('create');
        Route::post('/', [NotulenController::class, 'store'])->name('store');
        Route::get('/{notulen}', [NotulenController::class, 'show'])->name('show');
        Route::get('/{notulen}/edit', [NotulenController::class, 'edit'])->name('edit');
        Route::put('/{notulen}', [NotulenController::class, 'update'])->name('update');
        Route::delete('/{notulen}', [NotulenController::class, 'destroy'])->name('destroy');
    });

    // /agenda/create diarahkan ke index (form via modal)
    Route::get('/agenda/create', function () {
        return redirect()->route('agenda.index');
    })->name('agenda.create.redirect');

    // CRUD Agenda
    Route::resource('agenda', AgendaController::class);
});