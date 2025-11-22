<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roleUser extends Model
{
    use HasFactory;

    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
}
