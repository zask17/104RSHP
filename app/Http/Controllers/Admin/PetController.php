<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet; 
use App\Models\Pemilik; 
use App\Models\JenisHewan; 
use App\Models\RasHewan; 
use Illuminate\Validation\Rule;

class PetController extends Controller
{
    /**
     * Menampilkan daftar semua hewan peliharaan (pasien).
     */
    public function index()
    {
        // Eager load semua relasi
        $pets = Pet::with(['pemilik', 'jenisHewan', 'rasHewan'])->get();
        return view('admin.pets.index', compact('pets'));
    }

    /**
     * Menampilkan formulir untuk membuat hewan peliharaan baru.
     */
    public function create()
    {
        $pemilik = Pemilik::orderBy('nama_pemilik')->get();
        $jenisHewan = JenisHewan::orderBy('nama_jenis_hewan')->get();
        $rasHewan = RasHewan::all(); 

        return view('admin.pets.create', compact('pemilik', 'jenisHewan', 'rasHewan'));
    }

    /**
     * Menyimpan hewan peliharaan baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validatePet($request);

        Pet::create($validatedData);

        return redirect()->route('admin.pets.index')
                         ->with('success', 'Data Pasien (Pet) berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit hewan peliharaan tertentu.
     */
    public function edit($idpet) 
    {
        $pet = Pet::find($idpet); 

        // Tambahkan cek ini untuk menghindari error "Trying to get property of non-object"
        if (!$pet) {
            return redirect()->route('admin.pets.index')
                             ->with('error', 'Pasien tidak ditemukan.');
        }
        
        $pemilik = Pemilik::orderBy('nama_pemilik')->get();
        $jenisHewan = JenisHewan::orderBy('nama_jenis_hewan')->get();
        $rasHewan = RasHewan::where('idjenis_hewan', $pet->idjenis_hewan)->get(); 

        return view('admin.pets.edit', compact('pet', 'pemilik', 'jenisHewan', 'rasHewan'));
    }

    /**
     * Memperbarui hewan peliharaan tertentu di database.
     */
    public function update(Request $request, $idpet) 
    {
        $pet = Pet::find($idpet);

        if (!$pet) {
             return redirect()->route('admin.pets.index')
                             ->with('error', 'Pasien tidak ditemukan.');
        }
        
        $validatedData = $this->validatePet($request, $pet->idpet);

        $pet->update($validatedData);

        return redirect()->route('admin.pets.index')
                         ->with('success', 'Data Pasien (Pet) berhasil diperbarui.');
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
                             ->with('error', 'Gagal menghapus data pasien. Pastikan tidak ada rekam medis terkait.');
        }
    } // Pastikan kurung kurawal penutup ini ADA

    /**
     * Helper untuk validasi data Pet.
     */
    private function validatePet(Request $request, $id = null)
    {
        return $request->validate([
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
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'warna_tanda' => 'nullable|string|max:100', 
        ], [
            'idras_hewan.exists' => 'Ras hewan tidak cocok dengan jenis hewan yang dipilih.',
            'nama.required' => 'Nama pasien tidak boleh kosong.',
            'warna_tanda.max' => 'Warna/tanda maksimal 100 karakter.',
        ]);
    }
}