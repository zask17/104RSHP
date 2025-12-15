<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemilik; // Menggunakan model Pemilik
use Illuminate\Support\Facades\DB;

class PemilikController extends Controller
{
    /**
     * Menampilkan daftar semua Pemilik.
     */
    public function index()
    {
        $pemiliks = Pemilik::orderBy('idpemilik', 'desc')->get();
        return view('resepsionis.pemilik.index', compact('pemiliks'));
    }

    /**
     * Menampilkan form untuk membuat Pemilik baru.
     */
    public function create()
    {
        return view('resepsionis.pemilik.create');
    }

    /**
     * Menyimpan Pemilik baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:15|unique:pemilik,telepon',
        ]);

        try {
            Pemilik::create($request->all());
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data Pemilik berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan Pemilik: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit Pemilik.
     */
    public function edit(Pemilik $pemilik)
    {
        return view('resepsionis.pemilik.edit', compact('pemilik'));
    }

    /**
     * Memperbarui Pemilik di database.
     */
    public function update(Request $request, Pemilik $pemilik)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            // Pengecualian unique untuk data saat ini
            'telepon' => 'required|string|max:15|unique:pemilik,telepon,' . $pemilik->idpemilik . ',idpemilik',
        ]);

        try {
            $pemilik->update($request->all());
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data Pemilik berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui Pemilik: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus Pemilik dari database.
     */
    public function destroy(Pemilik $pemilik)
    {
        try {
            $pemilik->delete();
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data Pemilik berhasil dihapus!');
        } catch (\Exception $e) {
            // Cek jika ada foreign key constraint (misalnya, terkait dengan Pasien/Pet)
            if (DB::getDriverName() == 'mysql' && $e instanceof \Illuminate\Database\QueryException && $e->getCode() == 23000) {
                 return back()->with('error', 'Gagal menghapus Pemilik: Pemilik masih memiliki data Pasien/Pet yang terdaftar.');
            }
            return back()->with('error', 'Gagal menghapus Pemilik: ' . $e->getMessage());
        }
    }
}