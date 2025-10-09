<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('dokumens', function (Blueprint $table) {
      // Menambahkan kolom 'pic' setelah kolom 'alamat'
      $table->string('pic')->nullable()->after('alamat');
    });
  }

  public function down(): void
  {
    Schema::table('dokumens', function (Blueprint $table) {
      $table->dropColumn('pic');
    });
  }
};
