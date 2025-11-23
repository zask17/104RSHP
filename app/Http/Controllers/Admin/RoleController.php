<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna beserta rolenya.
     */
    public function index()
    {
        // Eager load relasi 'roles' untuk efisiensi query
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateRole($request);

        // Menggunakan helper untuk memastikan format nama yang benar
        $validatedData['nama_role'] = $this->formatNamaRole($validatedData['nama_role']);

        Role::create($validatedData);

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        // Menggunakan ID model untuk mengabaikan unique check pada dirinya sendiri
        $validatedData = $this->validateRole($request, $role->idrole);

        // Menggunakan helper untuk memastikan format nama yang benar
        $validatedData['nama_role'] = $this->formatNamaRole($validatedData['nama_role']);

        $role->update($validatedData);

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        // Pengecekan relasi dengan data User
        // Asumsi relasi user adalah hasMany (jika menggunakan idrole di tabel users)
        // if ($role->users()->count() > 0) { 
        //     return redirect()->route('admin.roles.index')
        //                      ->with('error', 'Gagal menghapus role karena masih digunakan oleh user.');
        // }

        try {
            $role->delete();
            return redirect()->route('admin.roles.index')
                             ->with('success', 'Role berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.roles.index')
                             ->with('error', 'Gagal menghapus role. Pastikan tidak ada data user yang terkait.');
        }
    }

    private function validateRole(Request $request, $id = null)
    {
        // Mendefinisikan rule unique dan mengecualikan ID saat update
        $uniqueRule = Rule::unique('role', 'nama_role');
        if ($id) {
            $uniqueRule->ignore($id, 'idrole');
        }

        return $request->validate([
            'nama_role' => [
                'required',
                'string',
                'min:3',
                'max:255',
                $uniqueRule,
            ],
        ], [
            'nama_role.required' => 'Nama role tidak boleh kosong.',
            'nama_role.min' => 'Nama role minimal 3 karakter.',
            'nama_role.max' => 'Nama role maksimal 255 karakter.',
            'nama_role.unique' => 'Nama role sudah ada.',
        ]);
    }

    protected function formatNamaRole($nama)
    {
        return trim(ucwords(strtolower($nama)));
    }
}