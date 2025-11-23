<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KategoriController extends Controller
{
    public function index()
    {
        // FIX: Menggunakan Eloquent Model (Kategori::all()) untuk mengambil data.
        // Lebih disukai daripada DB::table().
        $kategori = Kategori::all(); 

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        // Menggunakan ID model untuk mengabaikan unique check pada dirinya sendiri
        $validatedData = $this->validateKategori($request, $kategori->idkategori);

        // Menggunakan helper untuk memastikan format nama yang benar
        $validatedData['nama_kategori'] = $this->formatNamaKategori($validatedData['nama_kategori']);

        // FIX: Menggunakan Eloquent untuk update
        $kategori->nama_kategori = $validatedData['nama_kategori'];
        // Jika model Kategori memiliki timestamps, updated_at akan otomatis terisi.
        // Karena di model KodeTindakanTerapi timestamps = false, 
        // kita asumsikan di model Kategori juga false atau Anda ingin update manual.

        // Jika Anda TIDAK menggunakan timestamps di model Kategori, uncomment baris ini:
        // $kategori->updated_at = now(); 
        
        $kategori->save(); 
        
        return redirect()->route('admin.kategori-hewan.index')
                         ->with('success', 'Kategori hewan berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        // NOTE: Pengecekan relasi dengan model 'Hewan' (yang diasumsikan)
        // if ($kategori->hewan()->count() > 0) {
        //     return redirect()->route('admin.kategori-hewan.index')
        //                      ->with('error', 'Gagal menghapus kategori karena masih terkait dengan data hewan.');
        // }

        try {
            // FIX: Menggunakan Eloquent Model delete()
            $kategori->delete();
            return redirect()->route('admin.kategori-hewan.index')
                             ->with('success', 'Kategori hewan berhasil dihapus.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Gagal menghapus kategori: ' . $e->getMessage()); 

            return redirect()->route('admin.kategori-hewan.index')
                             ->with('error', 'Gagal menghapus kategori. Pastikan tidak ada data yang terkait.');
        }
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateKategori($request);

        // Menggunakan helper untuk memastikan format nama yang benar
        $validatedData['nama_kategori'] = $this->formatNamaKategori($validatedData['nama_kategori']);

        // FIX: Menggunakan Eloquent Model (Kategori::create)
        Kategori::create([
            'nama_kategori' => $validatedData['nama_kategori'],
            // created_at dan updated_at akan terisi otomatis jika timestamps aktif
        ]);

        return redirect()->route('admin.kategori-hewan.index')
                         ->with('success', 'Kategori hewan berhasil ditambahkan.');
    }

    private function validateKategori(Request $request, $id = null)
    {
        // Mendefinisikan rule unique dan mengecualikan ID saat update
        $uniqueRule = Rule::unique('kategori', 'nama_kategori');
        if ($id) {
            $uniqueRule->ignore($id, 'idkategori');
        }

        return $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'min:3',
                'max:255',
                $uniqueRule,
            ],
        ], [
            'nama_kategori.required' => 'Nama kategori tidak boleh kosong.',
            'nama_kategori.min' => 'Nama kategori minimal 3 karakter.',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter.',
            'nama_kategori.unique' => 'Nama kategori sudah ada.',
        ]);
    }

    protected function formatNamaKategori($nama)
    {
        return trim(ucwords(strtolower($nama)));
    }
}