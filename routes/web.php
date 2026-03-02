<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekamMedisController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Routes untuk Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes untuk Rekam Medis
    Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekam_medis.index');
    Route::get('/rekam-medis/create', [RekamMedisController::class, 'create'])->name('rekam_medis.create');
    Route::post('/rekam-medis', [RekamMedisController::class, 'store'])->name('rekam_medis.store');
    Route::post('/rekam-medis/sehat/{id_siswa}', [RekamMedisController::class, 'markAsHealthy'])->name('rekam_medis.sehat');
    
});


require __DIR__.'/auth.php';
