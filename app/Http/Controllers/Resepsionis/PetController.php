<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet; // Menggunakan model Pet
use App\Models\Pemilik; // Diperlukan untuk dropdown Pemilik
use App\Models\JenisHewan; // Diperlukan untuk dropdown Jenis Hewan
use App\Models\RasHewan; // Diperlukan untuk dropdown Ras Hewan
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    /**
     * Menampilkan daftar semua Pasien (Pets).
     */
    public function index()
    {
        // Mengambil semua data Pet beserta relasi pemilik, jenisHewan, dan rasHewan
        $pets = Pet::with(['pemilik', 'jenisHewan', 'rasHewan'])->orderBy('idpet', 'desc')->get();

        return view('resepsionis.pets.index', compact('pets'));
    }

    /**
     * Menampilkan form untuk membuat Pasien baru.
     */
    public function create()
    {
        // Ambil data untuk dropdown
        $pemiliks = Pemilik::orderBy('nama_pemilik')->get();
        $jenisHewans = JenisHewan::orderBy('nama_jenis_hewan')->get();
        $rasHewans = RasHewan::orderBy('nama_ras')->get();

        return view('resepsionis.pets.create', compact('pemiliks', 'jenisHewans', 'rasHewans'));
    }

    /**
     * Menyimpan Pasien baru ke database.
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
            return redirect()->route('resepsionis.pets.index')->with('success', 'Data Pasien berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan Pasien: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit Pasien.
     */
    public function edit(Pet $pet)
    {
        // Ambil data untuk dropdown
        $pemiliks = Pemilik::orderBy('nama_pemilik')->get();
        $jenisHewans = JenisHewan::orderBy('nama_jenis_hewan')->get();
        $rasHewans = RasHewan::orderBy('nama_ras')->get();

        return view('resepsionis.pets.edit', compact('pet', 'pemiliks', 'jenisHewans', 'rasHewans'));
    }

    /**
     * Memperbarui Pasien di database.
     */
    public function update(Request $request, Pet $pet)
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
            return redirect()->route('resepsionis.pets.index')->with('success', 'Data Pasien berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui Pasien: ' . $e->getMessage());
        }
    }

    // Ras Hewan yang muncul sesuai dengan Jenis Hewan yang dipilih (AJAX)
    public function getRasByJenis($id)
    {
        $ras = \App\Models\RasHewan::where('idjenis_hewan', $id)->orderBy('nama_ras')->get();
        return response()->json($ras);
    }

    /**
     * Menghapus Pasien dari database.
     */
    public function destroy(Pet $pet)
    {
        try {
            $pet->delete();
            return redirect()->route('resepsionis.pets.index')->with('success', 'Data Pasien berhasil dihapus!');
        } catch (\Exception $e) {
            // Cek jika ada foreign key constraint (misalnya, terkait dengan rekam medis)
            if (DB::getDriverName() == 'mysql' && $e instanceof \Illuminate\Database\QueryException && $e->getCode() == 23000) {
                return back()->with('error', 'Gagal menghapus Pasien: Data Pasien masih terkait dengan data lain (mis. Rekam Medis).');
            }
            return back()->with('error', 'Gagal menghapus Pasien: ' . $e->getMessage());
        }
    }
}
