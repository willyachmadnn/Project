<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotulenController;
use App\Http\Controllers\TamuController;
use Illuminate\Support\Facades\Route;

// Rute publik
Route::get('/', [AgendaController::class, 'publicIndex'])->name('home');

// Rute autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang dilindungi autentikasi
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::resource('agenda', AgendaController::class);
    
    // Rute untuk tamu agenda
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
    
    // Rute untuk notulen agenda
    Route::prefix('agenda/{agendaId}/notulen')->name('agenda.notulen.')->group(function () {
        Route::get('/create', [NotulenController::class, 'create'])->name('create');
        Route::post('/', [NotulenController::class, 'store'])->name('store');
        Route::get('/{id}', [NotulenController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [NotulenController::class, 'edit'])->name('edit');
        Route::put('/{id}', [NotulenController::class, 'update'])->name('update');
        Route::delete('/{id}', [NotulenController::class, 'destroy'])->name('destroy');
    });
});