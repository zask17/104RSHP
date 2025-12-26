<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemilik;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PemilikController extends Controller
{
    public function index()
    {
        $pemiliks = Pemilik::orderBy('idpemilik', 'asc')->get();
        return view('resepsionis.pemilik.index', compact('pemiliks'));
    }

    public function create()
    {
        return view('resepsionis.pemilik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'no_wa' => 'required|string|max:45',
            'alamat' => 'required|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat User baru (untuk login pemilik)
            $user = User::create([
                'nama' => $request->nama_pemilik,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 2. Berikan Role Pemilik (ID 5 berdasarkan database rshp)
            RoleUser::create([
                'iduser' => $user->iduser,
                'idrole' => 5, 
                'status' => 1
            ]);

            // 3. Simpan ke tabel pemilik
            Pemilik::create([
                'nama_pemilik' => $request->nama_pemilik,
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'iduser' => $user->iduser,
            ]);

            DB::commit();
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data Pemilik dan Akun User berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan Pemilik: ' . $e->getMessage());
        }
    }

    public function edit(Pemilik $pemilik)
    {
        return view('resepsionis.pemilik.edit', compact('pemilik'));
    }

    public function update(Request $request, Pemilik $pemilik)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'no_wa' => 'required|string|max:45',
            'alamat' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email,' . $pemilik->iduser . ',iduser',
        ]);

        try {
            DB::beginTransaction();

            // Update Tabel User terkait
            User::where('iduser', $pemilik->iduser)->update([
                'nama' => $request->nama_pemilik,
                'email' => $request->email,
            ]);

            // Update Tabel Pemilik
            $pemilik->update([
                'nama_pemilik' => $request->nama_pemilik,
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat,
                'email' => $request->email,
            ]);

            DB::commit();
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data Pemilik berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui Pemilik: ' . $e->getMessage());
        }
    }

    public function destroy(Pemilik $pemilik)
    {
        try {
            DB::beginTransaction();
            $iduser = $pemilik->iduser;

            $pemilik->delete();
            RoleUser::where('iduser', $iduser)->delete();
            User::where('iduser', $iduser)->delete();

            DB::commit();
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data Pemilik dan Akun terkait berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus Pemilik: ' . $e->getMessage());
        }
    }
    //  */ Relasi hasOne ke RoleUser (One-to-One)
     
    public function roleUser()
    {
        return $this->hasOne(RoleUser::class, 'iduser', 'iduser');
    }
}