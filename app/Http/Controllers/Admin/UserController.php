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

class UserController extends Controller
{
    public function index()
    {
        // Sekarang User::with('role') akan berfungsi karena sudah didefinisikan di Model
        $users = User::with('role')->get(); 
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        // Ambil ID role saat ini
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

            // 1. Update data User
            $user->nama = $request->nama;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = $request->password;
            }
            $user->save();

            // 2. Update role di tabel role_user
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
            
            // Hapus relasi role terlebih dahulu
            RoleUser::where('iduser', $user->iduser)->delete();
            
            // Hapus user utama
            $user->delete();

            DB::commit();
            return redirect()->route('admin.users.index')
                             ->with('success', 'User ' . $user->nama . ' berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.users.index')
                             ->with('error', 'Gagal menghapus user. Data mungkin masih terhubung dengan rekam medis atau pemilik.');
        }
    }
}