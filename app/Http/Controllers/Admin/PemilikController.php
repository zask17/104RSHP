<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PemilikController extends Controller
{
    public function index()
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
        // Validasi input
        $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'no_wa' => 'required|string|max:45',
            'email' => 'nullable|email|unique:user,email|max:100',
            'password' => 'required|min:6',
            'alamat' => 'required|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat User baru di tabel 'user'
            $user = User::create([
                'nama' => $request->nama_pemilik,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Enkripsi password
            ]);

            // 2. Hubungkan ke Role Pemilik (ID 5) di tabel 'role_user'
            // Inilah bagian yang memastikan role-nya adalah PEMILIK, bukan Resepsionis
            RoleUser::create([
                'iduser' => $user->iduser,
                'idrole' => 5, // 5 = Pemilik berdasarkan SQL Anda
                'status' => 1
            ]);

            // 3. Simpan detail informasi ke tabel 'pemilik'
            Pemilik::create([
                'nama_pemilik' => $request->nama_pemilik,
                'no_wa' => $request->no_wa,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'iduser' => $user->iduser, // Hubungkan dengan iduser yang baru dibuat
            ]);

            DB::commit();
            return redirect()->route('admin.pemilik.index')->with('success', 'Data pemilik berhasil ditambahkan dengan akses Role Pemilik.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $pemilik = Pemilik::findOrFail($id);
        return view('admin.pemilik.edit', compact('pemilik'));
    }

    public function update(Request $request, $id)
    {
        $pemilik = Pemilik::findOrFail($id);
        
        $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'no_wa' => 'required|string|max:45',
            'email' => 'nullable|email|unique:user,email,' . $pemilik->iduser . ',iduser|max:100',
            'alamat' => 'required|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Update User terkait
            $user = User::findOrFail($pemilik->iduser);
            $user->update([
                'nama' => $request->nama_pemilik,
                'email' => $request->email,
            ]);

            // Update data Pemilik
            $pemilik->update([
                'nama_pemilik' => $request->nama_pemilik,
                'no_wa' => $request->no_wa,
                'email' => $request->email,
                'alamat' => $request->alamat,
            ]);

            DB::commit();
            return redirect()->route('admin.pemilik.index')->with('success', 'Data pemilik berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $pemilik = Pemilik::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            $userId = $pemilik->iduser;
            
            // Hapus data pemilik
            $pemilik->delete();
            
            // Hapus relasi role
            RoleUser::where('iduser', $userId)->delete();
            
            // Hapus user
            User::where('iduser', $userId)->delete();

            DB::commit();
            return redirect()->route('admin.pemilik.index')->with('success', 'Data pemilik dan akun user berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}