<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';
    protected $primaryKey = 'iddokter'; // Kunci utama tabel 'dokter'
    public $timestamps = false; // Nonaktifkan timestamps

    /**
     * The attributes that are mass assignable.
     * Sesuaikan dengan kolom-kolom lain yang ada di tabel 'dokter'
     */
    protected $fillable = [
'alamat', 'no_hp', 'bidang_dokter', 'jenis_kelamin', 'id_user'];

    /**
     * Relasi belongsTo ke model User (One-to-One terbalik).
     * Foreign key 'id_user' di tabel 'dokter' merujuk ke 'iduser' di tabel 'user'.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'iduser');
    }
}