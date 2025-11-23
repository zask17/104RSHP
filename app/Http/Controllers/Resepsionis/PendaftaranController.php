<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Jika Anda memiliki model Pendaftaran, uncomment baris di bawah
// use App\Models\Pendaftaran;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman data pendaftaran.
     */
    public function index()
    {
        // Di sini Anda akan mengambil data dari database.
        // Untuk saat ini, kita akan menggunakan data contoh yang lebih detail.
        $pendaftarans = [
            [
                'no_urut' => 1,
                'waktu_daftar' => '2024-06-10 09:15:00',
                'nama_pet' => 'Snowy',
                'nama_pemilik' => 'Cassie',
                'nama_dokter' => 'Choi soobin'
            ],
            [
                'no_urut' => 2,
                'waktu_daftar' => '2024-12-25 09:30:00',
                'nama_pet' => 'Snowy',
                'nama_pemilik' => 'Cassie',
                'nama_dokter' => 'Choi soobin'
            ],
            // Tambahkan data pendaftaran lain di sini
        ];

        return view('resepsionis.pendaftaran', compact('pendaftarans'));
    }
}