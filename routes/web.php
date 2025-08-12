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
// PERBAIKAN: Menggunakan middleware 'auth' dengan guard 'admin'
Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Resourceful route untuk CRUD Agenda di dashboard admin
    Route::resource('agenda', AgendaController::class);

    // Rute untuk tamu yang berelasi dengan agenda
    Route::prefix('agenda/{agendaId}/tamu')->name('agenda.tamu.')->group(function () {
        Route::get('/', [TamuController::class, 'index'])->name('index');
        Route::get('/create', [TamuController::class, 'create'])->name('create');
        Route::post('/', [TamuController::class, 'store'])->name('store');
        Route::get('/{id}', [TamuController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [TamuController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TamuController::class, 'update'])->name('update');
        Route::delete('/{id}', [TamuController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/kehadiran', [TamuController::class, 'updateKehadiran'])->name('updateKehadiran');
    });

    // Rute untuk notulen yang berelasi dengan agenda
    Route::prefix('agenda/{agendaId}/notulen')->name('agenda.notulen.')->group(function () {
        Route::get('/create', [NotulenController::class, 'create'])->name('create');
        Route::post('/', [NotulenController::class, 'store'])->name('store');
        Route::get('/{id}', [NotulenController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [NotulenController::class, 'edit'])->name('edit');
        Route::put('/{id}', [NotulenController::class, 'update'])->name('update');
        Route::delete('/{id}', [NotulenController::class, 'destroy'])->name('destroy');
    });
});