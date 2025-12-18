<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardPerawatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil Profil Perawat (Sesuai skema: id_user & id_perawat)
        $profil = DB::table('perawat')
            ->join('user', 'perawat.id_user', '=', 'user.iduser')
            ->where('perawat.id_user', $userId)
            ->select('perawat.*', 'user.nama', 'user.email')
            ->first();

        if (!$profil) {
            $profil = (object) ['nama' => Auth::user()->nama, 'pendidikan' => 'Data Belum Lengkap', 'no_hp' => '-', 'jenis_kelamin' => '-'];
        }

        // 2. Data Pasien (Join ke pemilik & jenis hewan)
        $peliharaan = DB::table('pet')
            ->join('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
            ->join('jenis_hewan', 'pet.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->join('pemilik', 'pet.idpemilik', '=', 'pemilik.idpemilik')
            ->select('pet.*', 'ras_hewan.nama_ras', 'jenis_hewan.nama_jenis_hewan', 'pemilik.nama_pemilik')
            ->get();

        // 3. Rekam Medis (Join ke temu_dokter & role_user)
        $rekamMedis = DB::table('rekam_medis')
            ->join('temu_dokter', 'rekam_medis.idreservasi_dokter', '=', 'temu_dokter.idreservasi_dokter')
            ->join('pet', 'temu_dokter.idpet', '=', 'pet.idpet')
            ->join('role_user', 'rekam_medis.dokter_pemeriksa', '=', 'role_user.idrole_user')
            ->join('user as dokter_user', 'role_user.iduser', '=', 'dokter_user.iduser')
            ->select('rekam_medis.*', 'pet.nama as nama_pet', 'dokter_user.nama as nama_dokter', 'temu_dokter.tanggal_temu')
            ->orderBy('rekam_medis.created_at', 'desc')
            ->get();

        return view('perawat.dashboard-perawat', compact('profil', 'peliharaan', 'rekamMedis'));
    }

    public function destroyRekamMedis($id)
    {
        // Menghapus Detail terlebih dahulu karena ada Foreign Key Constraint
        DB::table('detail_rekam_medis')->where('idrekam_medis', $id)->delete();
        DB::table('rekam_medis')->where('idrekam_medis', $id)->delete();
        
        return redirect()->back()->with('success', 'Data rekam medis berhasil dihapus.');
    }
}