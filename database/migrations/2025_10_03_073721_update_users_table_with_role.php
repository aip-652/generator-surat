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
    // PERBAIKAN: Cek apakah kolom 'role' sudah ada sebelum mencoba menambahkannya
    Schema::table('users', function (Blueprint $table) {
      if (!Schema::hasColumn('users', 'role')) {
        $table->enum('role', ['admin', 'user'])->default('user')->after('password');
      }
    });
  }

  /**
   * Batalkan migrasi.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      // Cek apakah kolom 'role' ada sebelum mencoba menghapusnya
      if (Schema::hasColumn('users', 'role')) {
        $table->dropColumn('role');
      }
    });
  }
};
