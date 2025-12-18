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
      $table->enum('jenis_dokumen', ['memo_internal', 'surat_keluar']);
      $table->string('unit_kerja')->nullable(); // Kode Singkat Unit Kerja
      $table->string('kode_surat')->nullable(); // Kode Singkat Jenis Surat
      $table->string('nomor_dokumen')->unique();
      $table->string('perihal');
      $table->string('kepada')->nullable();
      $table->string('alamat')->nullable();
      $table->string('order')->nullable();
      $table->string('pic')->nullable();
      $table->longText('badan_surat')->nullable();
      //$table->string('email_requestor'); // Menggantikan PIC
      $table->date('tanggal');
      // Kolom 'substansi' dan 'order' sengaja dihilangkan
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
