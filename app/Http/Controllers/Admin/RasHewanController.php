<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RasHewan;
use App\Models\JenisHewan; 
use Illuminate\Validation\Rule;

class RasHewanController extends Controller
{
    public function index ()
    {
        // Mengambil semua RasHewan beserta JenisHewan terkait untuk tampilan tabel
        $rasHewan = RasHewan::with('jenis')->orderBy('idjenis_hewan')->get();
        
        // Cek jika rute bermasalah, jika tidak maka hanya kirim $rasHewan
        return view('admin.ras-hewan.index', compact('rasHewan'));
    }

    public function create()
    {
        // Mengambil semua jenis hewan untuk ditampilkan di dropdown
        $jenisHewan = JenisHewan::orderBy('nama_jenis_hewan')->get();
        return view('admin.ras-hewan.create', compact('jenisHewan'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateRasHewan($request);

        RasHewan::create($validatedData);

        return redirect()->route('admin.ras-hewan.index')
                         ->with('success', 'Ras hewan berhasil ditambahkan.');
    }

    public function edit(RasHewan $rasHewan)
    {
        $jenisHewan = JenisHewan::orderBy('nama_jenis_hewan')->get();
        return view('admin.ras-hewan.edit', compact('rasHewan', 'jenisHewan'));
    }

    public function update(Request $request, RasHewan $rasHewan)
    {
        // Gunakan ID rasHewan untuk validasi unique saat update
        $validatedData = $this->validateRasHewan($request, $rasHewan->idras_hewan);

        $rasHewan->update($validatedData);

        return redirect()->route('admin.ras-hewan.index')
                         ->with('success', 'Ras hewan berhasil diperbarui.');
    }

    public function destroy(RasHewan $rasHewan)
    {
        try {
            $rasHewan->delete();
            return redirect()->route('admin.ras-hewan.index')
                             ->with('success', 'Ras hewan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
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