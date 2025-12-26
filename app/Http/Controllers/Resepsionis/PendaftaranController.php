<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemuDokter; 
use App\Models\Pet;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman data pendaftaran/antrean hari ini.
     */
    public function index()
    {
        $today = now()->toDateString();

        $pendaftarans = TemuDokter::whereDate('tanggal_temu', $today)
            ->with([
                'pet.pemilik',
                'roleUser.user'
            ])
            ->orderBy('waktu_temu', 'asc')
            ->get();

        return view('resepsionis.pendaftaran.index', compact('pendaftarans', 'today'));
    }

    /**
     * Menampilkan form untuk membuat pendaftaran baru.
     */
    public function create()
    {
        $pets = Pet::with('pemilik')->get();
        
        // FIX: Memberikan kualifikasi tabel 'role.idrole' untuk menghindari ambiguitas
        $dokters = User::whereHas('roles', function ($query) {
            $query->where('role.idrole', 2); // ID Role Dokter = 2
        })->orderBy('nama')->get();

        return view('resepsionis.pendaftaran.create', compact('pets', 'dokters'));
    }

    /**
     * Menyimpan data pendaftaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idpet' => 'required|exists:pet,idpet',
            'iddokter' => 'required|exists:user,iduser',
            'alasan' => 'nullable|string|max:255',
        ]);

        $today = now()->toDateString();
        $currentTime = now()->toTimeString('minute');

        $roleUser = DB::table('role_user')
            ->where('iduser', $request->iddokter)
            ->where('idrole', 2)
            ->first();

        if (!$roleUser) {
            return back()->withInput()->with('error', 'Role Dokter untuk user ini tidak ditemukan.');
        }

        try {
            $lastTemu = TemuDokter::whereDate('tanggal_temu', $today)
                ->orderBy('no_urut', 'desc')
                ->first();
            $no_urut = ($lastTemu ? $lastTemu->no_urut : 0) + 1;

            TemuDokter::create([
                'idpet' => $request->idpet,
                'idrole_user' => $roleUser->idrole_user,
                'tanggal_temu' => $today,
                'waktu_temu' => $currentTime,
                'alasan' => $request->alasan,
                'status' => 'Pending',
                'no_urut' => $no_urut,
                'waktu_daftar' => now(),
            ]);

            return redirect()->route('resepsionis.pendaftaran.index')->with('success', 'Pasien berhasil didaftarkan! No. Urut: ' . $no_urut);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mendaftarkan pasien: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit pendaftaran.
     */
    public function edit($idreservasi_dokter)
    {
        $pendaftaran = TemuDokter::with(['pet.pemilik', 'roleUser.user'])->findOrFail($idreservasi_dokter);

        // FIX: Memberikan kualifikasi tabel 'role.idrole' untuk menghindari ambiguitas
        $dokters = User::whereHas('roles', function ($query) {
            $query->where('role.idrole', 2);
        })->orderBy('nama')->get();

        $pendaftaran->iddokter = $pendaftaran->roleUser->iduser ?? null;

        return view('resepsionis.pendaftaran.edit', compact('pendaftaran', 'dokters'));
    }

    /**
     * Memperbarui data pendaftaran.
     */
    public function update(Request $request, $idreservasi_dokter)
    {
        $pendaftaran = TemuDokter::findOrFail($idreservasi_dokter);

        $request->validate([
            'iddokter' => 'required|exists:user,iduser',
            'status' => 'required|in:Pending,Dikonfirmasi,Selesai,Dibatalkan',
        ]);

        $roleUser = DB::table('role_user')
            ->where('iduser', $request->iddokter)
            ->where('idrole', 2)
            ->first();

        if (!$roleUser) {
            return back()->withInput()->with('error', 'Role Dokter untuk user ini tidak ditemukan.');
        }

        try {
            $pendaftaran->update([
                'idrole_user' => $roleUser->idrole_user,
                'alasan' => $request->alasan,
                'status' => $request->status,
            ]);

            return redirect()->route('resepsionis.pendaftaran.index')->with('success', 'Data Pendaftaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui Pendaftaran: ' . $e->getMessage());
        }
    }

}