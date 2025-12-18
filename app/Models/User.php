<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Pemilik;
use App\Models\Dokter;
use App\Models\Perawat;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'idrole',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mutator to hash the password only if it needs rehashing.
     */
    public function setPasswordAttribute($password)
    {
        if ($password) {
            $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
        }
    }

    // /**
    //  * Relasi belongsTo untuk Role (One-to-One / Foreign Key on User table)
    //  */
    // public function role() // MENGAKTIFKAN RELASI INI
    // {
    //     return $this->belongsTo(Role::class, 'idrole', 'idrole');
    // }

    /**
     * PENTING: Gunakan hasOneThrough untuk mengambil Role tunggal 
     * melalui tabel perantara role_user. Ini untuk tampilan daftar pengguna.
     */
    public function role()
    {
        return $this->hasOneThrough(
            Role::class,
            RoleUser::class,
            'iduser',   // Foreign key di tabel role_user
            'idrole',   // Foreign key di tabel role
            'iduser',   // Local key di tabel user
            'idrole'    // Local key di tabel role_user
        );
    }

    
    /**
     * The roles that belong to the user. (Relasi Many-to-Many Anda yang lama)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'iduser', 'idrole')
            ->withPivot('status');
    }

    public function roleUser()
    {
        // Relasi ke tabel perantara role_user
        return $this->hasOne(RoleUser::class, 'iduser', 'iduser')
            ->where('status', 1);
    }

    /**
     * Get the Pemilik (Owner) associated with the user.
     * Uses 'iduser' as the foreign key in the Pemilik table.
     */
    public function pemilik()
    {
        return $this->hasOne(Pemilik::class, 'iduser', 'iduser');
    }

    /**
     * Relasi One-to-One: User has one Dokter.
     * Corrected foreign key from 'id' to 'iduser' for consistency.
     */
    public function dokter()
    {
        // Assuming the foreign key in the Dokter table is 'id_user' and the local key is 'iduser'
        return $this->hasOne(Dokter::class, 'id_user', 'iduser');
    }

    /**
     * Relasi One-to-One: User has one Perawat (Nurse).
     * Corrected foreign key from 'id' to 'iduser' for consistency.
     */
    public function perawat()
    {
        // Assuming the foreign key in the Perawat table is 'id_user' and the local key is 'iduser'
        return $this->hasOne(Perawat::class, 'id_user', 'iduser');
    }
}