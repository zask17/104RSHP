<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKlinis extends Model
{
    use HasFactory;

    protected $table = 'kategori_klinis';
    protected $primaryKey = 'idkategori_klinis';
    public $timestamps = false; // Diasumsikan tidak ada kolom created_at/updated_at

    protected $fillable = ['nama_kategori_klinis'];
}
