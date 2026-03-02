<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            // Menghapus kolom yang sudah tidak digunakan di alur baru
            $table->dropColumn(['status_izin', 'waktu_masuk', 'waktu_keluar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            // Mengembalikan kolom jika di-rollback
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_keluar')->nullable();
            $table->enum('status_izin', ['sakit', 'kembali ke kelas'])
                ->default('kembali ke kelas')
                ->comment('sakit = otomatis diabsen S oleh guru');
        });
    }
};
