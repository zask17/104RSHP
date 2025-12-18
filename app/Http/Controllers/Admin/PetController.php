<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\JenisHewan;
use App\Models\RasHewan;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    /**
     * Menampilkan daftar semua hewan peliharaan (pasien).
     */
    public function index()
    {
        // Eager load semua relasi
        $pets = Pet::with(['pemilik', 'jenisHewan', 'rasHewan'])
            ->orderBy('idpet', 'asc')
            ->get();


        return view('admin.pets.index', compact('pets'));
    }

    /**
     * Menampilkan formulir untuk membuat hewan peliharaan baru.
     */
    public function create()
    {
        $pemilik = Pemilik::orderBy('nama_pemilik')->get();
        $jenisHewan = JenisHewan::orderBy('nama_jenis_hewan')->get();
        $rasHewan = RasHewan::orderBy('nama_ras')->get();

        return view('admin.pets.create', compact('pemilik', 'jenisHewan', 'rasHewan'));
    }

    /**
     * Menyimpan hewan peliharaan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
            'idras_hewan' => 'nullable|exists:ras_hewan,idras_hewan',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'warna_tanda' => 'nullable|string|max:255',
        ]);

        try {
            // Model Mutator akan mengkonversi 'Jantan'/'Betina' menjadi '1'/'2'
            Pet::create($request->all());
            return redirect()->route('admin.pets.index')->with('success', 'Data Pasien berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan Pasien: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan formulir untuk mengedit hewan peliharaan tertentu.
     */
    public function edit($idpet)
    {
        // Ambil data untuk dropdown
        $pemiliks = Pemilik::orderBy('nama_pemilik')->get();
        $jenisHewans = JenisHewan::orderBy('nama_jenis_hewan')->get();
        $rasHewans = RasHewan::orderBy('nama_ras')->get();

        return view('admin.pets.edit', compact('pet', 'pemiliks', 'jenisHewans', 'rasHewans'));
    }

    /**
     * Memperbarui hewan peliharaan tertentu di database.
     */
    public function update(Request $request, $idpet)
    {
        $request->validate([
            'nama' => 'required|string',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
            'idras_hewan' => 'nullable|exists:ras_hewan,idras_hewan',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'warna_tanda' => 'nullable|string',
        ]);

        try {
            // Model Mutator akan mengkonversi 'Jantan'/'Betina' menjadi '1'/'2'
            $pet->update($request->all());
            return redirect()->route('admin.pets.index')->with('success', 'Data Pasien berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui Pasien: ' . $e->getMessage());
        }
    }


    // Ras Hewan yang muncul sesuai dengan Jenis Hewan yang dipilih (AJAX)
    public function getRasByJenis($id)
    {
        $ras = RasHewan::where('idjenis_hewan', $id)->orderBy('nama_ras', 'asc')->get();
        return response()->json($ras);
    }
    /**
     * Menghapus hewan peliharaan tertentu dari database.
     */
    public function destroy($idpet)
    {
        $pet = Pet::find($idpet);

        if (!$pet) {
            return redirect()->route('admin.pets.index')
                ->with('error', 'Pasien tidak ditemukan.');
        }

        try {
            $pet->delete();
            return redirect()->route('admin.pets.index')
                ->with('success', 'Data Pasien (Pet) berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pets.index')
                ->with('error', 'Gagal menghapus data pasien. Pastikan tidak ada rekam medis terkait. Error: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk validasi data Pet.
     */
    private function validatePet(Request $request, $id = null)
    {
        // Kita akan menggunakan 'nama' dan 'jenis_kelamin' yang sesuai dengan Model Mutator
        return $request->validate([
            // FIX: Ubah 'nama_pet' menjadi 'nama' (nama kolom DB)
            'nama' => 'required|string|max:255|min:2',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
            'idras_hewan' => [
                'required',
                Rule::exists('ras_hewan', 'idras_hewan')->where(function ($query) use ($request) {
                    return $query->where('idjenis_hewan', $request->idjenis_hewan);
                }),
            ],
            'tanggal_lahir' => 'nullable|date',
            // FIX: Ganti 'Laki-laki,Perempuan' ke 'Jantan,Betina' sesuai Model Pet.php
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            // FIX: Ubah 'warna' menjadi 'warna_tanda' (nama kolom DB)
            'warna_tanda' => 'nullable|string|max:100',
        ], [
            'idras_hewan.exists' => 'Ras hewan tidak cocok dengan jenis hewan yang dipilih.',
            'nama.required' => 'Nama pasien tidak boleh kosong.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Jantan atau Betina.',
            'warna_tanda.max' => 'Warna/tanda maksimal 100 karakter.',
        ]);
    }
}
