<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RasHewan;
use App\Models\JenisHewan; // Digunakan untuk mengambil data induk
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class RasHewanController extends Controller
{
    /**
     * Menampilkan daftar semua Jenis Hewan, beserta Ras yang terkait (termasuk yang kosong).
     */
    public function index ()
    {
        // Ambil SEMUA JenisHewan dan eager load RasHewan (ras) mereka.
        $jenisHewanWithRas = JenisHewan::with(['ras' => function ($query) {
            // Urutkan ras berdasarkan nama ras saat dimuat
            $query->orderBy('nama_ras');
        }])
        ->orderBy('nama_jenis_hewan')
        ->get(); 

        // Mengirim data yang sudah di-load ke view
        return view('admin.ras-hewan.index', [
            'jenisHewanWithRas' => $jenisHewanWithRas,
        ]);
    }
    
    // Metode CREATE DIHAPUS, karena penambahan dilakukan inline di index.

    /**
     * Menyimpan Ras Hewan yang baru dibuat ke database (Digunakan oleh form inline di index).
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateRasHewan($request);

        RasHewan::create($validatedData);

        return redirect()->route('admin.ras-hewan.index')
                         ->with('success', 'Ras hewan berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit Ras Hewan tertentu.
     */
    public function edit(RasHewan $rasHewan)
    {
        // Kita hanya perlu JenisHewan yang terkait untuk tampilan informasi, bukan dropdown.
        // Jika perlu dropdown untuk ganti jenis, gunakan baris di bawah:
        $jenisHewan = JenisHewan::orderBy('nama_jenis_hewan')->get();
        return view('admin.ras-hewan.edit', compact('rasHewan', 'jenisHewan'));
    }

    /**
     * Memperbarui Ras Hewan tertentu di database.
     */
    public function update(Request $request, RasHewan $rasHewan)
    {
        // Gunakan ID rasHewan untuk validasi unique saat update
        $validatedData = $this->validateRasHewan($request, $rasHewan->idras_hewan);

        $rasHewan->update($validatedData);

        return redirect()->route('admin.ras-hewan.index')
                         ->with('success', 'Ras hewan berhasil diperbarui.');
    }

    /**
     * Menghapus Ras Hewan dari database.
     */
    public function destroy(RasHewan $rasHewan)
    {
        try {
            $rasHewan->delete();
            return redirect()->route('admin.ras-hewan.index')
                             ->with('success', 'Ras hewan berhasil dihapus.');
        } catch (QueryException $e) {
             return redirect()->route('admin.ras-hewan.index')
                             ->with('error', 'Gagal menghapus ras hewan karena masih terkait dengan data lain (misal: data hewan peliharaan).');
        }
    }

    /**
     * Helper untuk validasi data Ras Hewan.
     */
    private function validateRasHewan(Request $request, $id = null)
    {
        $uniqueRule = Rule::unique('ras_hewan', 'nama_ras');
        if ($id) {
            $uniqueRule->ignore($id, 'idras_hewan');
        }
        
        return $request->validate([
            'nama_ras' => [
                'required',
                'string',
                'min:3',
                'max:255',
                $uniqueRule,
            ],
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
        ], [
            'nama_ras.required' => 'Nama ras tidak boleh kosong.',
            'nama_ras.min' => 'Nama ras minimal 3 karakter.',
            'nama_ras.unique' => 'Nama ras sudah ada.',
            'idjenis_hewan.required' => 'Jenis hewan harus dipilih.',
            'idjenis_hewan.exists' => 'Jenis hewan yang dipilih tidak valid.',
        ]);
    }
}