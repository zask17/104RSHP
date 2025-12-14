<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RasHewan extends Model
{
    // use HasFactory; // Diberi komentar di file Anda, saya biarkan
    
    protected $table = 'ras_hewan';
    protected $primaryKey = 'idras_hewan';
    public $timestamps = false;

    protected $fillable = ['nama_ras', 'idjenis_hewan'];

    public function jenis()
    {
        // Pastikan Anda memiliki Model JenisHewan.php
        return $this->belongsTo(JenisHewan::class, 'idjenis_hewan', 'idjenis_hewan');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'idras_hewan', 'idras_hewan');
    }
}