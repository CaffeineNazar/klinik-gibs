<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    // Arahkan ke koneksi db_gibs
    protected $connection = 'mysql_gibs'; 
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    public $timestamps = false; // Karena tabel siswa tidak punya created_at/updated_at di sql Anda
}
