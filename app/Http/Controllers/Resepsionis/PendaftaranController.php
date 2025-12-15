<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// FIX: Gunakan Model Pendaftaran yang baru
use App\Models\Pendaftaran; 

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman data pendaftaran/antrean hari ini.
     */
    public function index()
    {
        // 1. Ambil tanggal hari ini
        $today = now()->toDateString();
        
        // 2. Ambil data pendaftaran (temu_dokter) hari ini
        // Menggunakan Eager Loading untuk Pet, Pemilik, RoleUser, dan User (Dokter)
        $pendaftarans = Pendaftaran::where('tanggal_temu', $today)
                            ->whereIn('status', ['Pending', 'Dikonfirmasi']) // Hanya yang aktif di antrean
                            ->with([
                                'pet.pemilik', 
                                'roleUser.user' // Dokter
                            ])
                            ->orderBy('waktu_temu', 'asc') // Urutkan berdasarkan waktu temu
                            ->get();

        return view('resepsionis.pendaftaran.index', compact('pendaftarans', 'today'));
    }
}