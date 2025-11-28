<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoleUser; // Import model RoleUser
use Illuminate\Support\Facades\Log;

class RoleUserController extends Controller
{
    /**
     * Menampilkan daftar semua entri di tabel role_user.
     * Ini berguna untuk melihat semua role yang dimiliki oleh setiap user (terutama jika Many-to-Many).
     */
    public function index()
    {
        // Eager load relasi 'role' dan 'user' untuk menampilkan data lengkap
        // Pastikan relasi 'user' didefinisikan di RoleUser.php jika ingin di-eager load.
        // Jika hanya ingin melihat Role, kita hanya perlu eager load 'role'.
        $roleUsers = RoleUser::with('role')->get();

        // Logika tampilan di sini (misalnya, jika Anda memiliki view 'admin.role_user.index')
        // return view('admin.role_user.index', compact('roleUsers'));
        
        // Untuk saat ini, kita hanya akan mengembalikan respons dasar karena tidak ada view yang diberikan.
        return response()->json([
            'message' => 'Daftar relasi User-Role berhasil diambil.',
            'data' => $roleUsers
        ]);
    }
    
    // Menambahkan method 'store', 'destroy', dll. di sini jika controller ini 
    // memang ditujukan untuk mengelola penambahan/penghapusan role sekunder.

    // Contoh method store (jika diperlukan untuk menambahkan role sekunder)
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'iduser' => 'required|exists:user,iduser',
            'idrole' => 'required|exists:role,idrole',
            'status' => 'required|boolean',
        ]);

        try {
            RoleUser::create($validated);
            return back()->with('success', 'Role sekunder berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan RoleUser: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan role sekunder.');
        }
    }
    
}