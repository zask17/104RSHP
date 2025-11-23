<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    protected $table = 'pemilik';
    protected $primaryKey = 'idpemilik';
    public $timestamps = false;

    protected $fillable = [
        'nama_pemilik', 
        'alamat', 
        'no_hp', 
        'email',
    ];


        public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'iduser');
    }
    public function pets()
    {
        return $this->hasMany(Pet::class, 'idpemilik', 'idpemilik');
    }  

    
}
