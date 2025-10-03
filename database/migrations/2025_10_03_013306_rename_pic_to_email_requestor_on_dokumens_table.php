<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Mengubah nama kolom 'pic' menjadi 'email_requestor'.
     */
    public function up(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            // Pastikan kolom pic sudah ada sebelum di-rename
            if (Schema::hasColumn('dokumens', 'pic')) {
                $table->renameColumn('pic', 'email_requestor');
            } else {
                // Jika kolom 'pic' tidak ada (misal di-drop di migrasi lain), tambahkan 'email_requestor'
                $table->string('email_requestor')->nullable()->after('alamat');
            }
        });
    }

    /**
     * Mengembalikan migrasi (roll back).
     * Mengubah nama kolom 'email_requestor' kembali menjadi 'pic'.
     */
    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            if (Schema::hasColumn('dokumens', 'email_requestor')) {
                $table->renameColumn('email_requestor', 'pic');
            }
        });
    }
};