<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pendaftaran (Antrean)
 * Model ini merepresentasikan antrean pasien yang dijadwalkan (Temu Dokter).
 * Menggunakan tabel 'temu_dokter' sebagai sumber data.
 */
class Pendaftaran extends Model
{
    use HasFactory;

    // Tabel yang digunakan adalah 'temu_dokter'
    protected $table = 'temu_dokter';
    
    // Primary key dari tabel temu_dokter
    protected $primaryKey = 'idreservasi_dokter'; 
    
    // Non-incrementing primary key jika diperlukan, tapi kita biarkan default auto-increment
    
    // Karena tabel temu_dokter tidak memiliki kolom created_at/updated_at default
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * Sesuaikan dengan kolom-kolom di tabel temu_dokter.
     */
    protected $fillable = [
        'no_urut',
        'waktu_daftar',
        'status',
        'idpet',
        'idrole_user',
        'tanggal_temu',
        'waktu_temu',
        'alasan',
    ];

    /**
     * Relasi belongsTo ke model Pet (Pasien).
     * Kolom FK di tabel ini adalah idpet.
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }

    /**
     * Relasi belongsTo ke model RoleUser (untuk mendapatkan informasi Dokter).
     * Kolom FK di tabel ini adalah idrole_user.
     */
    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class, 'idrole_user', 'idrole_user');
    }

    /**
     * Akses langsung ke Dokter (User) melalui RoleUser.
     * Digunakan untuk eager loading: Pendaftaran::with('dokter.user')
     */
    public function dokter()
    {
        return $this->roleUser()->with('user');
    }
}