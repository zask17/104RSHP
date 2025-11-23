<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori; // Diperlukan untuk relasi belongsTo
use App\Models\User; // Diperlukan jika hewan memiliki pemilik (user)

class Hewan extends Model
{
    use HasFactory;

    protected $table = 'hewan';
    protected $primaryKey = 'idhewan';
    
    /**
     * The attributes that are mass assignable.
     * Sesuaikan dengan kolom-kolom di tabel 'hewan' Anda.
     */
    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'berat',
        'idkategori', // Foreign key ke tabel 'kategori'
        'iduser',     // Foreign key ke tabel 'user' (Pemilik)
    ];

    /**
     * Relasi belongsTo ke model Kategori (One-to-One terbalik/Many-to-One).
     * Kategori::class sudah diimpor di atas.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'idkategori', 'idkategori');
    }
    
    /**
     * Relasi belongsTo ke model User (Pemilik Hewan).
     * User::class sudah diimpor di atas.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    /**
     * Relasi One-to-Many: Hewan memiliki banyak Rekam Medis (asumsi).
     */
    // public function rekamMedis()
    // {
    //     return $this->hasMany(RekamMedis::class, 'idhewan', 'idhewan');
    // }
}