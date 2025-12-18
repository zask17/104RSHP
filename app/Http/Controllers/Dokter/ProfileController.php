<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $iduser = Auth::id();

        // Ambil data user, role, dan detail dokter
        $userProfile = DB::table('user')
            ->join('role_user', 'user.iduser', '=', 'role_user.iduser')
            ->join('role', 'role_user.idrole', '=', 'role.idrole')
            ->leftJoin('dokter', 'user.iduser', '=', 'dokter.id_user')
            ->where('user.iduser', $iduser)
            ->where('role.idrole', 2) // Pastikan idrole 2 adalah Dokter
            ->select(
                'user.nama', 
                'user.email', 
                'role.nama_role', 
                'role_user.status as status_role',
                'role_user.idrole_user',
                'dokter.alamat', 
                'dokter.no_hp', 
                'dokter.bidang_dokter', 
                'dokter.jenis_kelamin'
            )
            ->first();

        // Hitung statistik rekam medis
        $jumlah_rekam_medis = DB::table('rekam_medis')
            ->where('dokter_pemeriksa', $userProfile->idrole_user)
            ->count();

        return view('dokter.profile.index', compact('userProfile', 'jumlah_rekam_medis'));
    }

    public function edit()
    {
        $iduser = Auth::id();
        $user = DB::table('user')->where('iduser', $iduser)->first();
        $dokter = DB::table('dokter')->where('id_user', $iduser)->first();

        return view('dokter.profile.edit', compact('user', 'dokter'));
    }

    public function update(Request $request)
    {
        $iduser = Auth::id();

        $request->validate([
            'nama' => 'required|string|max:500',
            'no_hp' => 'nullable|string|max:45',
            'alamat' => 'nullable|string|max:100',
            'bidang_dokter' => 'nullable|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        DB::transaction(function () use ($request, $iduser) {
            // 1. Update Tabel User
            DB::table('user')->where('iduser', $iduser)->update([
                'nama' => $request->nama,
            ]);

            // 2. Update atau Insert Tabel Dokter
            DB::table('dokter')->updateOrInsert(
                ['id_user' => $iduser],
                [
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'bidang_dokter' => $request->bidang_dokter,
                    'jenis_kelamin' => $request->jenis_kelamin,
                ]
            );
        });

        return redirect()->route('dokter.profile.index')->with('success', 'Profil berhasil diperbarui!');
    }
}