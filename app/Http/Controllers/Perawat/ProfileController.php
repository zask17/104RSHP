<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $iduser = Auth::id();

        // Mengambil data profil perawat sesuai skema database (id_user & id_perawat)
        $userProfile = DB::table('user')
            ->join('role_user', 'user.iduser', '=', 'role_user.iduser')
            ->join('role', 'role_user.idrole', '=', 'role.idrole')
            ->leftJoin('perawat', 'user.iduser', '=', 'perawat.id_user')
            ->where('user.iduser', $iduser)
            ->where('role.idrole', 3) // ID 3 adalah Perawat
            ->select(
                'user.nama', 
                'user.email', 
                'role.nama_role', 
                'role_user.status as status_role',
                'perawat.alamat', 
                'perawat.no_hp', 
                'perawat.pendidikan', 
                'perawat.jenis_kelamin'
            )
            ->first();

        return view('perawat.pofile.index', compact('userProfile'));
    }
}