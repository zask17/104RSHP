<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Hewan;

class Kategori extends Model
{
    use HasFactory;
    
    protected $table = 'kategori';
    protected $primaryKey = 'idkategori';
    protected $fillable = ['nama_kategori'];
    public $timestamps = false;

    
    /**
     * Relasi One-to-Many: Kategori memiliki banyak Hewan.
     */
    public function hewan()
    {
        // Model Hewan kini dapat dikenali
        return $this->hasMany(Hewan::class, 'idkategori', 'idkategori');
    }
}