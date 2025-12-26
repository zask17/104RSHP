<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemuDokter;
use App\Models\Pet; 
use App\Models\User; 
use Illuminate\Support\Facades\DB;

class TemuDokterController extends Controller
{
    /**
     * Menampilkan daftar semua Temu Dokter.
     */
    public function index()
    {
        // FIX: Menggunakan 'idreservasi_dokter' untuk pengurutan
        $temuDokters = TemuDokter::whereDate('tanggal_temu', '!=', now()->toDateString()) // Hanya Janji Temu non-harian
                            ->with(['pet.pemilik', 'roleUser.user'])
                            ->orderBy('tanggal_temu', 'desc') 
                            ->orderBy('waktu_temu', 'desc')
                            ->get();
        
        return view('resepsionis.temu-dokter.index', compact('temuDokters'));
    }

    /**
     * Menampilkan form untuk membuat Temu Dokter baru.
     */
    public function create()
    {
        $pets = Pet::with('pemilik')->get(); // Ambil semua pasien
        // Ambil user yang ber-role dokter (ID Role Dokter = 2)
        $dokters = User::whereHas('roles', function($query) {
                        // FIX AMBIGUITAS: Kualifikasi kolom 'role.idrole'
                        $query->where('role.idrole', 2); 
                    })->orderBy('nama')->get(); 

        return view('resepsionis.temu-dokter.create', compact('pets', 'dokters'));
    }

    /**
     * Menyimpan Temu Dokter baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idpet' => 'required|exists:pet,idpet',
            'iddokter' => 'required|exists:user,iduser', 
            'tanggal_temu' => 'required|date',
            'waktu_temu' => 'required|date_format:H:i',
            'alasan' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,Dikonfirmasi,Selesai,Dibatalkan',
        ]);
        
        // Cari idrole_user
        $roleUser = DB::table('role_user')
                        ->where('iduser', $request->iddokter)
                        ->where('idrole', 2) // Asumsi ID Role Dokter adalah 2
                        ->first();

        if (!$roleUser) {
            return back()->withInput()->with('error', 'Role Dokter untuk user ini tidak ditemukan.');
        }

        try {
            // Logika sederhana untuk no_urut (ambil no urut terakhir hari ini)
            $lastTemu = TemuDokter::whereDate('tanggal_temu', $request->tanggal_temu)
                                  ->orderBy('no_urut', 'desc')
                                  ->first();
            $no_urut = ($lastTemu ? $lastTemu->no_urut : 0) + 1;

            TemuDokter::create([
                'idpet' => $request->idpet,
                'idrole_user' => $roleUser->idrole_user, // Simpan ID ROLE_USER
                'tanggal_temu' => $request->tanggal_temu,
                'waktu_temu' => $request->waktu_temu,
                'alasan' => $request->alasan,
                'status' => $request->status,
                'no_urut' => $no_urut,
                'waktu_daftar' => now(),
            ]);
            
            return redirect()->route('resepsionis.temu-dokter.index')->with('success', 'Janji Temu berhasil dijadwalkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menjadwalkan Janji Temu: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit Temu Dokter.
     */
    public function edit(TemuDokter $temuDokter)
    {
        $pets = Pet::with('pemilik')->get();
        // Ambil user yang ber-role dokter (ID Role Dokter = 2)
        $dokters = User::whereHas('roles', function($query) {
                        // FIX AMBIGUITAS: Kualifikasi kolom 'role.idrole'
                        $query->where('role.idrole', 2); 
                    })->orderBy('nama')->get(); 
        
        // Dapatkan ID USER dari idrole_user yang tersimpan
        $temuDokter->iddokter = $temuDokter->roleUser->iduser ?? null;
        
        return view('resepsionis.temu-dokter.edit', compact('temuDokter', 'pets', 'dokters'));
    }

    /**
     * Memperbarui Temu Dokter di database.
     */
    public function update(Request $request, TemuDokter $temuDokter)
    {
        $request->validate([
            'idpet' => 'required|exists:pet,idpet',
            'iddokter' => 'required|exists:user,iduser',
            'tanggal_temu' => 'required|date',
            'waktu_temu' => 'required|date_format:H:i',
            'alasan' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,Dikonfirmasi,Selesai,Dibatalkan',
        ]);
        
        // Cari idrole_user
        $roleUser = DB::table('role_user')
                        ->where('iduser', $request->iddokter)
                        ->where('idrole', 2) // Asumsi ID Role Dokter adalah 2
                        ->first();

        if (!$roleUser) {
            return back()->withInput()->with('error', 'Role Dokter untuk user ini tidak ditemukan.');
        }

        try {
            $temuDokter->update([
                'idpet' => $request->idpet,
                'idrole_user' => $roleUser->idrole_user, // Simpan ID ROLE_USER
                'tanggal_temu' => $request->tanggal_temu,
                'waktu_temu' => $request->waktu_temu,
                'alasan' => $request->alasan,
                'status' => $request->status,
            ]);
            
            return redirect()->route('resepsionis.temu-dokter.index')->with('success', 'Janji Temu berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui Janji Temu: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus Temu Dokter dari database.
     */
    // public function destroy(TemuDokter $temuDokter)
    // {
    //     try {
    //         $temuDokter->delete();
    //         return redirect()->route('resepsionis.temu-dokter.index')->with('success', 'Janji Temu berhasil dihapus!');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Gagal menghapus Janji Temu: ' . $e->getMessage());
    //     }
    // }
}