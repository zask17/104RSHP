<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeliharaanController extends Controller
{
    /**
     * Menampilkan daftar pet yang dimiliki oleh user yang sedang login.
     */
    public function index()
    {
        // Mengambil ID user yang sedang login
        $userId = Auth::id();

        // Mengambil data pets yang 'iduser' pada relasi pemiliknya
        // sama dengan ID user yang sedang login.
        // Eager load relasi untuk efisiensi query (menghindari N+1 problem).
        $pets = Pet::with(['rasHewan.jenis'])
                    ->whereHas('pemilik', function ($query) use ($userId) {
                        $query->where('iduser', $userId);
                    })->get();

        return view('pemilik.index', compact('pets'));
    }
}