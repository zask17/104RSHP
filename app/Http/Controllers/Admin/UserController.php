<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mod\App\Models\RoleUser;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        // Eager load relasi 'role' (yang belongsTo)
        $users = User::with('role')->get(); 
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Validasi tanpa ID (create mode)
        $validatedData = $this->validateUser($request);

        // Jika Model User.php TIDAK memiliki mutator setPasswordAttribute($password)
        // Maka Anda perlu melakukan hashing secara manual di sini:
        // $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Catatan: Menggunakan $user->id untuk Rule::unique, pastikan konsisten dengan primary key 'iduser'
        $validatedData = $this->validateUser($request, $user->iduser); 

        // Hanya update password jika diisi
        if (isset($validatedData['password']) && !empty($validatedData['password'])) {
            // Mutator di model akan melakukan hashing jika ada.
            // Jika tidak ada, tambahkan: $validatedData['password'] = Hash::make($validatedData['password']);
            $user->password = $validatedData['password']; 
        } else {
            unset($validatedData['password']);
        }
        
        $user->update($validatedData);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Pencegahan penghapusan diri sendiri
        if (auth()->id() == $user->iduser) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                             ->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Gagal menghapus user. Terjadi kesalahan sistem atau masih ada data terkait.');
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