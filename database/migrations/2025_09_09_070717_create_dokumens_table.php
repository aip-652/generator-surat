<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Jalankan migrasi.
   */
  public function up(): void
  {
    Schema::create('dokumens', function (Blueprint $table) {
      $table->id();
      
      // Jenis dokumen
      $table->enum('jenis_dokumen', ['memo_internal', 'surat_keluar']);

      // ID dokumen
      $table->string('nomor_dokumen')->unique();
      $table->string('kode_surat')->nullable();
      $table->date('tanggal');

      // Struktur organisasi
      $table->string('unit_kerja')->nullable();
      $table->string('tujuan')->nullable();
      $table->string('dari')->nullable();
      $table->string('order')->nullable();
      $table->string('pic')->nullable();

      // Konten dokumen
      $table->string('perihal');
      $table->string('lampiran')->nullable();
      $table->text('tembusan')->nullable();
      $table->longText('badan_surat')->nullable();
      
      $table->timestamps();
    });
  }

  /**
   * Batalkan migrasi.
   */
  public function down(): void
  {
    Schema::dropIfExists('dokumens');
  }
};
