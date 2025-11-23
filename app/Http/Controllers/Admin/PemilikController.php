<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemilik;
use Illuminate\Validation\Rule;

class PemilikController extends Controller
{
       public function index ()
    {
       $pemilik = Pemilik::all();
        return view('admin.pemilik.index', compact('pemilik'));
    }

    public function create()
    {
        return view('admin.pemilik.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validatePemilik($request);

        Pemilik::create($validatedData);

        return redirect()->route('admin.pemilik.index')
                         ->with('success', 'Data pemilik berhasil ditambahkan.');
    }

    public function edit(Pemilik $pemilik)
    {
        return view('admin.pemilik.edit', compact('pemilik'));
    }

    public function update(Request $request, Pemilik $pemilik)
    {
        // Menggunakan ID model untuk mengabaikan unique check pada dirinya sendiri
        $validatedData = $this->validatePemilik($request, $pemilik->idpemilik);

        $pemilik->update($validatedData);

        return redirect()->route('admin.pemilik.index')
                         ->with('success', 'Data pemilik berhasil diperbarui.');
    }

    public function destroy(Pemilik $pemilik)
    {
        // Pengecekan relasi dengan data hewan peliharaan
        if ($pemilik->pets()->count() > 0) {
            return redirect()->route('admin.pemilik.index')
                             ->with('error', 'Gagal menghapus pemilik karena masih memiliki data hewan peliharaan yang terdaftar.');
        }
        try {
            $pemilik->delete();
            return redirect()->route('admin.pemilik.index')
                             ->with('success', 'Data pemilik berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pemilik.index')
                             ->with('error', 'Gagal menghapus pemilik. Terjadi kesalahan sistem.');
        }
    }

    private function validatePemilik(Request $request, $id = null)
    {
        return $request->validate([
            'nama_pemilik' => 'required|string|max:255|min:3',
            'alamat' => 'required|string|max:500',
            'no_hp' => [
                'required',
                'string',
                'max:15',
                Rule::unique('pemilik', 'no_hp')->ignore($id, 'idpemilik'),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('pemilik', 'email')->ignore($id, 'idpemilik'),
            ],
        ], [
            'nama_pemilik.required' => 'Nama pemilik tidak boleh kosong.',
            'alamat.required' => 'Alamat tidak boleh kosong.',
            'no_hp.required' => 'Nomor HP tidak boleh kosong.',
            'no_hp.unique' => 'Nomor HP sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);
    }
}