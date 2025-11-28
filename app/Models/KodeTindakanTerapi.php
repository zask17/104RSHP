<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;
use App\Models\KategoriKlinis;

class KodeTindakanTerapi extends Model
{
    use HasFactory;

    protected $table = 'kode_tindakan_terapi';
    protected $primaryKey = 'idkode_tindakan_terapi';
    public $timestamps = false;

    // Fillable fields
    protected $fillable = ['kode', 'deskripsi_tindakan_terapi', 'idkategori', 'idkategori_klinis'];

    /**
     * Get the kategori (category) that owns the tindakan terapi.
     */
    public function kategori()
    {
        // The Kategori class is now correctly resolved
        return $this->belongsTo(Kategori::class, 'idkategori', 'idkategori');
    }


    public function kategoriKlinis()
    {
        return $this->belongsTo(KategoriKlinis::class, 'idkategori_klinis', 'idkategori_klinis');
    }
}