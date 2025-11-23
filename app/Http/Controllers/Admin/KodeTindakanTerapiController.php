<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KodeTindakanTerapi;
use App\Models\Kategori; // Diperlukan untuk dropdown
use App\Models\KategoriKlinis; // Diperlukan untuk dropdown
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KodeTindakanTerapiController extends Controller
{
    /**
     * Menampilkan daftar semua kode tindakan terapi.
     */
    public function index()
    {
        // Eager load the relationships to prevent N+1 query issues
        $kodeTindakanTerapi = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])->get();

        return view('admin.kode-tindakan-terapi.index', compact('kodeTindakanTerapi'));
    }

    /**
     * Menampilkan formulir untuk membuat kode baru.
     */
    public function create()
    {
        $kategori = Kategori::all();
        $kategoriKlinis = KategoriKlinis::all();
        return view('admin.kode-tindakan-terapi.create', compact('kategori', 'kategoriKlinis'));
    }

    /**
     * Menyimpan kode baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateKodeTindakanTerapi($request);

        KodeTindakanTerapi::create($validatedData);

        return redirect()->route('admin.kode-tindakan-terapi.index')
                         ->with('success', 'Kode Tindakan/Terapi berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit kode tertentu.
     */
    public function edit(KodeTindakanTerapi $kodeTindakanTerapi)
    {
        $kategori = Kategori::all();
        $kategoriKlinis = KategoriKlinis::all();
        return view('admin.kode-tindakan-terapi.edit', compact('kodeTindakanTerapi', 'kategori', 'kategoriKlinis'));
    }

    /**
     * Memperbarui kode tertentu di database.
     */
    public function update(Request $request, KodeTindakanTerapi $kodeTindakanTerapi)
    {
        // Menggunakan ID model untuk mengabaikan unique check pada dirinya sendiri
        $validatedData = $this->validateKodeTindakanTerapi($request, $kodeTindakanTerapi->idkode_tindakan_terapi);

        $kodeTindakanTerapi->update($validatedData);

        return redirect()->route('admin.kode-tindakan-terapi.index')
                         ->with('success', 'Kode Tindakan/Terapi berhasil diperbarui.');
    }

    /**
     * Menghapus kode tertentu dari database.
     */
    public function destroy(KodeTindakanTerapi $kodeTindakanTerapi)
    {
        // NOTE: Tambahkan pengecekan relasi dengan data lain (misal: rekam medis) jika ada.

        try {
            $kodeTindakanTerapi->delete();
            return redirect()->route('admin.kode-tindakan-terapi.index')
                             ->with('success', 'Kode Tindakan/Terapi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.kode-tindakan-terapi.index')
                             ->with('error', 'Gagal menghapus kode tindakan. Pastikan tidak ada data yang terkait.');
        }
    }

    /**
     * Helper untuk validasi data Kode Tindakan Terapi.
     */
    private function validateKodeTindakanTerapi(Request $request, $id = null)
    {
        // Mendefinisikan rule unique untuk 'kode'
        $uniqueRule = Rule::unique('kode_tindakan_terapi', 'kode');
        if ($id) {
            $uniqueRule->ignore($id, 'idkode_tindakan_terapi');
        }

        return $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                $uniqueRule,
            ],
            'deskripsi' => 'required|string|max:500',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ], [
            'kode.required' => 'Kode tindakan tidak boleh kosong.',
            'kode.unique' => 'Kode tindakan sudah ada.',
            'deskripsi.required' => 'Deskripsi tindakan tidak boleh kosong.',
            'idkategori.required' => 'Kategori harus dipilih.',
            'idkategori_klinis.required' => 'Kategori klinis harus dipilih.',
        ]);
    }
}