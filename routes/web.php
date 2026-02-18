<?php

use App\Http\Controllers\DokumenController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk halaman login
Route::get('/', function () {
    return view('auth.login');
});

// Grup untuk SEMUA PENGGUNA yang sudah login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DokumenController::class, 'adminDashboard'])->name('dashboard');

    // Rute profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Rute untuk membuat dokumen
    Route::get('/memo/create', [DokumenController::class, 'createMemo'])->name('dokumen.create.memo');
    Route::post('/memo', [DokumenController::class, 'storeMemo'])->name('dokumen.store.memo');
    Route::get('/surat/create', [DokumenController::class, 'createSuratKeluar'])->name('dokumen.create.surat');
    Route::post('/surat', [DokumenController::class, 'storeSuratKeluar'])->name('dokumen.store.surat');
    Route::get('/dokumen/{dokumen}/pdf', [DokumenController::class, 'downloadPdf'])->name('dokumen.pdf');

    // Edit
    Route::get('/dokumen/{dokumen}/edit', [DokumenController::class, 'edit'])->name('dokumen.edit');
    Route::put('/dokumen/{dokumen}', [DokumenController::class, 'update'])->name('dokumen.update');
});

// Grup HANYA untuk ADMIN dan SPECIAL
Route::middleware(['auth', 'verified', 'role:admin,special'])->group(function () {
    // Rute untuk dokumen backdate
    Route::get('/dokumen/backdate', [DokumenController::class, 'createBackdate'])->name('dokumen.create.backdate');

    Route::post('/dokumen/backdate/memo', [DokumenController::class, 'storeBackdateMemo'])->name('dokumen.store.backdate.memo');

    Route::post('/dokumen/backdate/surat', [DokumenController::class, 'storeBackdateSurat'])->name('dokumen.store.backdate.surat');
});

// Grup HANYA untuk ADMIN
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::delete('/dokumen/{dokumen}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');

    // Rute Manajemen User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Rute untuk melihat log
    Route::get('/logs', [AdminLogController::class, 'index'])->name('logs.index');
});

require __DIR__ . '/auth.php';
