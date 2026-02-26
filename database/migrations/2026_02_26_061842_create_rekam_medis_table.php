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
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id('id_rekam_medis');
            
            // Relasi ke db_gibs (Lintas Database)
            $table->integer('id_siswa')->comment('Nempel dengan id_siswa di db_gibs');
            $table->integer('id_perawat')->nullable()->comment('Nempel dengan id_user di db_gibs');
            
            // Waktu Kunjungan
            $table->date('tanggal');
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_keluar')->nullable();
            
            // Rekam Medis Dasar
            $table->text('keluhan')->nullable();
            $table->string('diagnosa')->nullable();
            $table->text('tindakan')->nullable();
            
            // Status yang akan dibaca oleh Proyek Presensi
            $table->enum('status_izin', ['sakit', 'kembali ke kelas'])
                  ->default('kembali ke kelas')
                  ->comment('sakit = otomatis diabsen S oleh guru');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};