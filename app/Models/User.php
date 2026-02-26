<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // Hapus HasApiTokens di sini, cukup HasFactory dan Notifiable
    use HasFactory, Notifiable;

    // 1. Arahkan model ini untuk menggunakan koneksi db_gibs
    protected $connection = 'mysql_gibs';
    
    // 2. Arahkan ke nama tabel yang benar
    protected $table = 'users';
    
    // 3. Beritahu Laravel bahwa primary key-nya adalah id_user
    protected $primaryKey = 'id_user';

    // 4. Matikan timestamps karena tabel users di db_gibs tidak punya kolom 'updated_at'
    public $timestamps = false; 

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'profile',
    ];

    // Sembunyikan password saat data diambil
    protected $hidden = [
        'password',
    ];
}