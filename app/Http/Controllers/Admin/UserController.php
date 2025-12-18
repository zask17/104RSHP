<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // Eager load 'role' menggunakan hasOneThrough yang sudah dibuat
        $users = User::with('role')->get(); 
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        // Ambil ID role saat ini dari tabel role_user
        $currentRoleId = RoleUser::where('iduser', $user->iduser)->value('idrole');
        return view('admin.users.edit', compact('user', 'roles', 'currentRoleId'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required', 'email',
                Rule::unique('user', 'email')->ignore($user->iduser, 'iduser'),
            ],
            'idrole' => 'required|exists:role,idrole',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // 1. Update data User
        $user->nama = $request->nama;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = $request->password;
        }
        $user->save();

        // 2. Update atau Create data di tabel role_user
        RoleUser::updateOrCreate(
            ['iduser' => $user->iduser],
            ['idrole' => $request->idrole, 'status' => 1]
        );

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

        public function destroy($id)
    {
        // Cari user berdasarkan primary key 'iduser'
        $user = User::findOrFail($id);

        // 1. Pencegahan: Jangan biarkan user menghapus akunnya sendiri yang sedang login
        if (Auth::id() == $user->iduser) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Gagal! Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.');
        }

        try {
            // 2. Hapus data di tabel perantara 'role_user' terlebih dahulu
            // Ini untuk menghindari error constraint integritas database
            RoleUser::where('iduser', $user->iduser)->delete();

            // 3. Hapus data utama di tabel 'user'
            $user->delete();

            return redirect()->route('admin.users.index')
                             ->with('success', 'User ' . $user->nama . ' berhasil dihapus dari sistem.');
        } catch (\Exception $e) {
            // Tangani jika ada error (misal user masih terikat dengan data rekam medis/pemilik)
            return redirect()->route('admin.users.index')
                             ->with('error', 'Gagal menghapus user. Data user ini mungkin masih terhubung dengan catatan rekam medis atau data lainnya.');
        }
    }

    private function validateUser(Request $request, $id = null)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('user', 'email')->ignore($id, 'iduser'), // Menggunakan tabel 'user' dan primary key 'iduser'
            ],
            
            'idrole' => 'required|exists:role,idrole', 
            'password' => [
                $id ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
        $messages = [
            'idrole.required' => 'Role pengguna harus dipilih.',
            'idrole.exists' => 'Role pengguna yang dipilih tidak valid.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
        ];

        return $request->validate($rules, $messages);
    }

}