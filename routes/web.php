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

// QR Code Public Routes - untuk form tamu dari scan QR code
Route::get('/tamu/create', [TamuController::class, 'createPublic'])->name('tamu.create.public');
Route::post('/tamu/create', [TamuController::class, 'storePublic'])->name('tamu.store.public');
Route::get('/tamu/success', [TamuController::class, 'success'])->name('tamu.success');

// Direct routes untuk form pegawai dan non-pegawai (untuk tombol "daftar lagi")
Route::get('/tamu/pegawai', [TamuController::class, 'createPublic'])->name('tamu.pegawai');
Route::get('/tamu/non-pegawai', [TamuController::class, 'createPublic'])->name('tamu.non-pegawai');

// Auth (admin)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:admin')->name('logout');

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
        Route::put('/{tamu:id_tamu}', [TamuController::class, 'update'])->name('update');
        Route::delete('/{tamu:id_tamu}', [TamuController::class, 'destroy'])->name('destroy');

        // Route untuk halaman kelola tamu
        Route::get('/kelola', [TamuController::class, 'kelolaTamu'])->name('kelola');

        // Route untuk halaman tambah OPD
        Route::get('/tambah-opd', [TamuController::class, 'tambahOpd'])->name('tambah-opd');
        Route::get('/opd-diundang', [TamuController::class, 'opdDiundang'])->name('opd-diundang');
        Route::post('/simpan-opd', [TamuController::class, 'simpanOpd'])->name('simpan-opd');
        Route::delete('/hapus-opd/{opd_id}', [TamuController::class, 'hapusOpd'])->name('hapus-opd');

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