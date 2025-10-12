<?php

use App\Http\Controllers\DokumenController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
  return view('auth.login');
});

// Grup untuk SEMUA PENGGUNA yang sudah login
Route::middleware(['auth', 'verified'])->group(function () {
  // Rute dashboard utama untuk semua role
  Route::get('/dashboard', [DokumenController::class, 'adminDashboard'])->name('dashboard');

  // Rute profil pengguna
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  
  // Rute untuk membuat dokumen
  Route::get('/memo/create', [DokumenController::class, 'createMemo'])->name('dokumen.create.memo');
  Route::post('/memo', [DokumenController::class, 'storeMemo'])->name('dokumen.store.memo');
  Route::get('/surat/create', [DokumenController::class, 'createSuratKeluar'])->name('dokumen.create.surat');
  Route::post('/surat', [DokumenController::class, 'storeSuratKeluar'])->name('dokumen.store.surat');
});

// Grup HANYA untuk ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
  // Rute manajemen dokumen
  Route::delete('/dokumen/{dokumen}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');
  
  // Rute Manajemen User
  Route::get('/users', [UserController::class, 'index'])->name('users.index');
  Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
  Route::post('/users', [UserController::class, 'store'])->name('users.store');
  Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
  Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
  Route::delete('/users/{users}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__ . '/auth.php';
