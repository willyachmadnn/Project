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
    // RUTE-RUTE BERELASI (NESTED ROUTES)
    // Didefinisikan terlebih dahulu sesuai best practice.
    // =====================================================================

    // RUTE UNTUK TAMU (BERELASI DENGAN AGENDA)
    Route::prefix('agenda/{agenda}/tamu')->name('agenda.tamu.')->group(function () {
        Route::get('/', [TamuController::class, 'index'])->name('index');
        Route::post('/', [TamuController::class, 'store'])->name('store');
        Route::put('/{tamu:NIP}', [TamuController::class, 'update'])->name('update');
        Route::delete('/{tamu:NIP}', [TamuController::class, 'destroy'])->name('destroy');

        // FIX: Redirect untuk link 'create' yang mungkin masih ada,
        // mengarahkan ke halaman index di mana modal berada.
        Route::get('/create', function (App\Models\Agenda $agenda) {
            return redirect()->route('agenda.tamu.index', $agenda);
        })->name('create');
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

    // =====================================================================
    // RUTE UTAMA UNTUK AGENDA
    // =====================================================================

    // FIX: Redirect untuk route 'agenda.create' agar mengarah ke halaman utama
    // karena form sekarang menggunakan modal.
    Route::get('/agenda/create', function () {
        return redirect()->route('agenda.index');
    })->name('agenda.create.redirect');

    // Resourceful route untuk CRUD Agenda.
    // Rute 'create' dan 'edit' secara teknis masih ada, tetapi tidak digunakan oleh UI.
    Route::resource('agenda', AgendaController::class);
});
