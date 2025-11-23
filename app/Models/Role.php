<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $table = 'role'; 
    protected $primaryKey = 'idrole'; 
    protected $fillable = ['idrole', 'nama_role'];
    

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'idrole', 'iduser')
        ->withPivot('status');
    }
}
