<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', function () {
  return view('landing');
});


// Routes untuk Memo Internal
Route::get('/memo/create', [DokumenController::class, 'createMemo'])->name('dokumen.create.memo');
Route::post('/memo', [DokumenController::class, 'storeMemo'])->name('dokumen.store.memo');

// Routes untuk Surat Keluar
Route::get('/surat/create', [DokumenController::class, 'createSuratKeluar'])->name('dokumen.create.surat');
Route::post('/surat', [DokumenController::class, 'storeSuratKeluar'])->name('dokumen.store.surat');

// Routes untuk admin dashboard dengan password
Route::group(['middleware' => ['auth']], function () {
  Route::get('/admin/dashboard', [DokumenController::class, 'adminDashboard'])->name('admin.dashboard');
});