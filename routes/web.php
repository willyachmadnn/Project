<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotulenController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditugaskan ke grup middleware "web".
|
*/

// --- RUTE PUBLIK ---
Route::get('/', [PageController::class, 'index'])->name('landing');
Route::get('/api/agenda-counts', [PageController::class, 'getAgendaCounts'])->name('api.agenda.counts');


// --- RUTE AUTENTIKASI ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- RUTE ADMIN YANG DILINDUNGI ---
Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // =====================================================================
    // BEST PRACTICE: Definisikan rute yang lebih spesifik (nested) terlebih dahulu
    // untuk menghindari potensi konflik dengan rute resource yang lebih umum.
    // =====================================================================

    // RUTE UNTUK TAMU (BERELASI DENGAN AGENDA)
    Route::prefix('agenda/{agenda}/tamu')->name('agenda.tamu.')->group(function () {
        Route::get('/', [TamuController::class, 'index'])->name('index');
        Route::post('/', [TamuController::class, 'store'])->name('store');
        Route::put('/{tamu:NIP}', [TamuController::class, 'update'])->name('update');
        Route::delete('/{tamu:NIP}', [TamuController::class, 'destroy'])->name('destroy');
    });

    // RUTE UNTUK NOTULEN (BERELASI DENGAN AGENDA)
    Route::prefix('agenda/{agenda}/notulen')->name('agenda.notulen.')->group(function () {
        Route::get('/create', [NotulenController::class, 'create'])->name('create');
        Route::post('/', [NotulenController::class, 'store'])->name('store');
        Route::get('/{notulen}', [NotulenController::class, 'show'])->name('show');
        Route::get('/{notulen}/edit', [NotulenController::class, 'edit'])->name('edit');
        Route::put('/{notulen}', [NotulenController::class, 'update'])->name('update');
        Route::delete('/{notulen}', [NotulenController::class, 'destroy'])->name('destroy');
    });

    // Resourceful route untuk CRUD Agenda. Diletakkan setelah rute yang lebih spesifik.
    Route::resource('agenda', AgendaController::class);
});
