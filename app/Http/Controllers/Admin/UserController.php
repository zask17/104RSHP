<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get(); 
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user (Sesuai error yang Anda alami)
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Menyimpan user baru ke database rshp
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:500',
            'email' => 'required|email|unique:user,email',
            'idrole' => 'required|exists:role,idrole',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan ke tabel 'user'
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => $request->password, // Password di-hash otomatis oleh mutator di model User
            ]);

            // 2. Hubungkan ke tabel 'role_user'
            RoleUser::create([
                'iduser' => $user->iduser,
                'idrole' => $request->idrole,
                'status' => 1
            ]);

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(User $user)
    {
        $roles = Role::all();
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

        try {
            DB::beginTransaction();

            $user->nama = $request->nama;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = $request->password;
            }
            $user->save();

            RoleUser::updateOrCreate(
                ['iduser' => $user->iduser],
                ['idrole' => $request->idrole, 'status' => 1]
            );

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() == $user->iduser) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Gagal! Anda tidak dapat menghapus akun Anda sendiri.');
        }

        try {
            DB::beginTransaction();
            RoleUser::where('iduser', $user->iduser)->delete();
            $user->delete();
            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.users.index')->with('error', 'Gagal menghapus user.');
        }
    }
}