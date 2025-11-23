<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriKlinis;
use Illuminate\Validation\Rule;

class KategoriKlinisController extends Controller
{
    public function index()
    {
        $kategoriKlinis = KategoriKlinis::all();
        return view('admin.kategori-klinis.index', compact('kategoriKlinis'));
    }

    public function create()
    {
        return view('admin.kategori-klinis.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateKategoriKlinis($request);

        // Menggunakan helper untuk memastikan format nama yang benar
        $validatedData['nama_kategori_klinis'] = $this->formatNamaKategoriKlinis($validatedData['nama_kategori_klinis']);

        KategoriKlinis::create($validatedData);

        return redirect()->route('admin.kategori-klinis.index')
                         ->with('success', 'Kategori klinis berhasil ditambahkan.');
    }

    public function edit(KategoriKlinis $kategoriKlinis)
    {
        return view('admin.kategori-klinis.edit', compact('kategoriKlinis'));
    }

    public function update(Request $request, KategoriKlinis $kategoriKlinis)
    {
        // Menggunakan ID model untuk mengabaikan unique check pada dirinya sendiri
        $validatedData = $this->validateKategoriKlinis($request, $kategoriKlinis->idkategori_klinis);

        // Menggunakan helper untuk memastikan format nama yang benar
        $validatedData['nama_kategori_klinis'] = $this->formatNamaKategoriKlinis($validatedData['nama_kategori_klinis']);

        $kategoriKlinis->update($validatedData);

        return redirect()->route('admin.kategori-klinis.index')
                         ->with('success', 'Kategori klinis berhasil diperbarui.');
    }

    public function destroy(KategoriKlinis $kategoriKlinis)
    {
        // NOTE: Tambahkan pengecekan relasi dengan data lain (misal: rekam medis) jika ada.

        try {
            $kategoriKlinis->delete();
            return redirect()->route('admin.kategori-klinis.index')
                             ->with('success', 'Kategori klinis berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.kategori-klinis.index')
                             ->with('error', 'Gagal menghapus kategori klinis. Pastikan tidak ada data yang terkait.');
        }
    }

    private function validateKategoriKlinis(Request $request, $id = null)
    {
        // Mendefinisikan rule unique dan mengecualikan ID saat update
        $uniqueRule = Rule::unique('kategori_klinis', 'nama_kategori_klinis');
        if ($id) {
            $uniqueRule->ignore($id, 'idkategori_klinis');
        }

        return $request->validate([
            'nama_kategori_klinis' => [
                'required',
                'string',
                'min:3',
                'max:255',
                $uniqueRule,
            ],
        ], [
            'nama_kategori_klinis.required' => 'Nama kategori klinis tidak boleh kosong.',
            'nama_kategori_klinis.min' => 'Nama kategori klinis minimal 3 karakter.',
            'nama_kategori_klinis.max' => 'Nama kategori klinis maksimal 255 karakter.',
            'nama_kategori_klinis.unique' => 'Nama kategori klinis sudah ada.',
        ]);
    }

    protected function formatNamaKategoriKlinis($nama)
    {
        return trim(ucwords(strtolower($nama)));
    }
}