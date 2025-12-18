<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    public function index()
    {
        $iduser = Auth::id();
        $dokter = DB::table('role_user')->where('iduser', $iduser)->where('idrole', 2)->first();

        $temuDokters = DB::table('temu_dokter')
            ->join('pet', 'temu_dokter.idpet', '=', 'pet.idpet')
            ->join('pemilik', 'pet.idpemilik', '=', 'pemilik.idpemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->leftJoin('rekam_medis', 'temu_dokter.idreservasi_dokter', '=', 'rekam_medis.idreservasi_dokter')
            ->where('temu_dokter.idrole_user', $dokter->idrole_user)
            ->select('temu_dokter.*', 'pet.nama as nama_pet', 'user.nama as nama_pemilik', 'rekam_medis.idrekam_medis')
            ->orderBy('tanggal_temu', 'desc')
            ->paginate(10);

        return view('dokter.rekam_medis.index', compact('temuDokters'));
    }

    public function create($id)
    {
        $temuDokter = DB::table('temu_dokter')
            ->join('pet', 'temu_dokter.idpet', '=', 'pet.idpet')
            ->join('pemilik', 'pet.idpemilik', '=', 'pemilik.idpemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->where('idreservasi_dokter', $id)
            ->select('temu_dokter.*', 'pet.nama as nama_pet', 'user.nama as nama_pemilik')
            ->first();

        return view('dokter.rekam_medis.create', compact('temuDokter'));
    }

    public function store(Request $request)
    {
        $idrole_user_dokter = DB::table('role_user')->where('iduser', Auth::id())->where('idrole', 2)->value('idrole_user');

        $idrekam = DB::table('rekam_medis')->insertGetId([
            'created_at' => now(),
            'anamnesa' => $request->anamnesa,
            'temuan_klinis' => $request->temuan_klinis,
            'diagnosa' => $request->diagnosa,
            'idreservasi_dokter' => $request->idreservasi_dokter,
            'dokter_pemeriksa' => $idrole_user_dokter,
        ]);

        DB::table('temu_dokter')->where('idreservasi_dokter', $request->idreservasi_dokter)->update(['status' => 'Selesai']);

        return redirect()->route('dokter.rekam-medis.show', $idrekam)->with('success', 'Rekam Medis berhasil dibuat.');
    }

    public function show($id)
    {
        $rekamMedis = DB::table('rekam_medis')
            ->join('temu_dokter', 'rekam_medis.idreservasi_dokter', '=', 'temu_dokter.idreservasi_dokter')
            ->join('pet', 'temu_dokter.idpet', '=', 'pet.idpet')
            ->join('role_user', 'rekam_medis.dokter_pemeriksa', '=', 'role_user.idrole_user')
            ->join('user', 'role_user.iduser', '=', 'user.iduser')
            ->where('rekam_medis.idrekam_medis', $id)
            ->select('rekam_medis.*', 'pet.nama as nama_pet', 'user.nama as nama_dokter')
            ->first();

        $details = DB::table('detail_rekam_medis')
            ->join('kode_tindakan_terapi', 'detail_rekam_medis.idkode_tindakan_terapi', '=', 'kode_tindakan_terapi.idkode_tindakan_terapi')
            ->join('kategori', 'kode_tindakan_terapi.idkategori', '=', 'kategori.idkategori')
            ->where('idrekam_medis', $id)
            ->select('detail_rekam_medis.*', 'kode_tindakan_terapi.kode', 'kode_tindakan_terapi.deskripsi_tindakan_terapi', 'kategori.nama_kategori')
            ->get();

        $kodeTindakan = DB::table('kode_tindakan_terapi')->get();

        return view('dokter.rekam_medis.show', compact('rekamMedis', 'details', 'kodeTindakan'));
    }
}