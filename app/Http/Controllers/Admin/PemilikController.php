<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemilik;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
        // Validasi sekarang mencakup password jika ini adalah operasi store
        $validatedData = $this->validatePemilik($request);

        try {
            // 1. Buat user baru untuk pemilik terlebih dahulu
            $user = User::create([
                'nama' => $validatedData['nama_pemilik'],
                'email' => $validatedData['email'] ?? null,
                // Menggunakan password dari input user, bukan password default
                'password' => bcrypt($validatedData['password']), 
            ]);

            // 2. Buat data pemilik dengan iduser dari user yang baru dibuat
            $pemilik = Pemilik::create([
                'nama_pemilik' => $validatedData['nama_pemilik'],
                'alamat' => $validatedData['alamat'],
                'no_wa' => $validatedData['no_wa'],
                'email' => $validatedData['email'] ?? null,
                'iduser' => $user->iduser,
            ]);

            // 3. Assign role 'Pemilik' (idrole = 4, pastikan ada di database)
            RoleUser::create([
                'iduser' => $user->iduser,
                'idrole' => 4, // Asumsikan role pemilik memiliki idrole = 4
                'status' => 1, // 1 = active, 0 = inactive
            ]);

            return redirect()->route('admin.pemilik.index')
                             ->with('success', 'Data pemilik berhasil ditambahkan dan user account telah dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pemilik.index')
                             ->with('error', 'Gagal menambahkan data pemilik: ' . $e->getMessage());
        }
    }

    public function edit(Pemilik $pemilik)
    {
        return view('admin.pemilik.edit', compact('pemilik'));
    }

    public function update(Request $request, Pemilik $pemilik)
    {
        // Menggunakan ID model untuk mengabaikan unique check pada dirinya sendiri
        $validatedData = $this->validatePemilik($request, $pemilik->idpemilik);

        try {
            // Update data pemilik
            $pemilik->update($validatedData);

            // Update data user jika ada
            if ($pemilik->iduser) {
                $user = User::find($pemilik->iduser);
                if ($user) {
                    $user->update([
                        'nama' => $validatedData['nama_pemilik'],
                        'email' => $validatedData['email'] ?? null,
                    ]);
                }
            }

            return redirect()->route('admin.pemilik.index')
                             ->with('success', 'Data pemilik berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pemilik.index')
                             ->with('error', 'Gagal memperbarui data pemilik: ' . $e->getMessage());
        }
    }

    public function destroy(Pemilik $pemilik)
    {
        // Pengecekan relasi dengan data hewan peliharaan
        if ($pemilik->pets()->count() > 0) {
            return redirect()->route('admin.pemilik.index')
                             ->with('error', 'Gagal menghapus pemilik karena masih memiliki data hewan peliharaan yang terdaftar.');
        }
        try {
            $userId = $pemilik->iduser;
            
            // Hapus data pemilik
            $pemilik->delete();

            // Hapus user dan role_user jika ada
            if ($userId) {
                RoleUser::where('iduser', $userId)->delete();
                User::destroy($userId);
            }

            return redirect()->route('admin.pemilik.index')
                             ->with('success', 'Data pemilik dan user account berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pemilik.index')
                             ->with('error', 'Gagal menghapus pemilik. Terjadi kesalahan sistem.');
        }
    }

    private function validatePemilik(Request $request, $id = null)
    {
        $rules = [
            'nama_pemilik' => 'required|string|max:255|min:3',
            'alamat' => 'required|string|max:500',
            'no_wa' => [
                'required',
                'string',
                'max:15',
                Rule::unique('pemilik', 'no_wa')->ignore($id, 'idpemilik'),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                // Rule::unique('pemilik', 'email') tidak mencukupi, perlu ditambahkan rule unique untuk tabel user jika diperlukan validasi yang lebih ketat.
                Rule::unique('pemilik', 'email')->ignore($id, 'idpemilik'), 
            ],
            'iduser' => 'nullable|integer|exists:users,iduser',
        ];

        $messages = [
            'nama_pemilik.required' => 'Nama pemilik tidak boleh kosong.',
            'alamat.required' => 'Alamat tidak boleh kosong.',
            'no_wa.required' => 'Nomor HP tidak boleh kosong.',
            'no_wa.unique' => 'Nomor HP sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
        ];

        // Tambahkan aturan validasi password hanya untuk operasi store (ketika $id = null)
        if ($id === null) {
            $rules['password'] = 'required|string|min:6';
            $messages['password.required'] = 'Password tidak boleh kosong.';
            $messages['password.min'] = 'Password minimal 6 karakter.';
        }

        return $request->validate($rules, $messages);
    }
}