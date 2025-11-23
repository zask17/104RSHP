<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perawat extends Model
{
    use HasFactory;

    protected $table = 'perawat';
    protected $primaryKey = 'idperawat'; // Kunci utama tabel 'perawat'
    public $timestamps = false; // Nonaktifkan timestamps

    /**
     * The attributes that are mass assignable.
     * Sesuaikan dengan kolom-kolom lain yang ada di tabel 'perawat'
     */
    protected $fillable = [
        'tingkat_pendidikan',
        'id_user', // Foreign key ke tabel 'user'
    ];

    /**
     * Relasi belongsTo ke model User (One-to-One terbalik).
     * Foreign key 'id_user' di tabel 'perawat' merujuk ke 'iduser' di tabel 'user'.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'iduser');
    }
}