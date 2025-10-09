<?php

use App\Http\Controllers\DokumenController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('auth.login');
});

Route::get('/dashboard', [DokumenController::class, 'adminDashboard'])
->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/landing', function () {
    return view('landing');
  })->name('landing');
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  
  // Routes untuk Memo Internal
  Route::get('/memo/create', [DokumenController::class, 'createMemo'])->name('dokumen.create.memo');
  Route::post('/memo', [DokumenController::class, 'storeMemo'])->name('dokumen.store.memo');
  
  // Routes untuk Surat Keluar
  Route::get('/surat/create', [DokumenController::class, 'createSuratKeluar'])->name('dokumen.create.surat');
  Route::post('/surat', [DokumenController::class, 'storeSuratKeluar'])->name('dokumen.store.surat');
  
  // // Routes untuk admin dashboard dengan password
  // Route::group(['middleware' => ['auth']], function () {
  //   Route::get('/admin/dashboard', [DokumenController::class, 'adminDashboard'])->name('admin.dashboard');
  // });
});


require __DIR__ . '/auth.php';
