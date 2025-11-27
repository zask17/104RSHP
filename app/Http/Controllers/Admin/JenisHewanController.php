<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisHewan;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class JenisHewanController extends Controller
{
    public function index ()
    {
        $jenisHewan = DB::table('jenis_hewan')
        ->select('idjenis_hewan', 'nama_jenis_hewan')
        ->get(); 

        return view('admin.jenis-hewan.index', compact('jenisHewan'));
    }

    public function create()
    {
        // Path view diperbaiki (dihapus duplikasi 'create/')
        return view('admin.jenis-hewan.create.create');
    }

    public function store(Request $request) //Digunakan untuk MENYIMPAN DATA
    {
        // Validasi input tanpa ID (untuk operasi buat baru)
        $validatedData = $this->validateJenisHewan($request); 
        
        // Panggil helper untuk membuat data baru (menggunakan helper Anda)
        $this->createJenisHewan($validatedData);

        return redirect()->route('admin.jenis-hewan.index')
                         ->with('success', 'Jenis hewan berhasil ditambahkan.');
    }

    public function edit(JenisHewan $jenisHewan)
    {
        // Path view diperbaiki untuk menunjuk ke 'edit/edit.blade.php'
        return view('admin.jenis-hewan.edit.edit', compact('jenisHewan'));
    }

    public function update(Request $request, JenisHewan $jenisHewan)
    {
        // Panggil validasi dengan ID model (untuk mengecualikan instance saat unique check)
        $validatedData = $this->validateJenisHewan($request, $jenisHewan->idjenis_hewan);

        // Update data dengan data yang sudah divalidasi(Menggunakan Query Builder)
        DB::table('jenis_hewan')
           ->where('idjenis_hewan', $jenisHewan->idjenis_hewan)
           ->update([
            'nama_jenis_hewan'=> $this->formatNamaJenisHewan($validatedData['nama_jenis_hewan']),
           ]);

        return redirect()->route('admin.jenis-hewan.index')
                         ->with('success', 'Jenis hewan berhasil diperbarui.');
    }

    public function destroy(JenisHewan $jenisHewan)
    {
        try {
         DB::table('jenis_hewan')
            ->where('idjenis_hewan',$jenisHewan->idjenis_hewan)
            ->delete();

            return redirect()->route('admin.jenis-hewan.index')
                             ->with('success', 'Jenis hewan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Menangkap error foreign key constraint
            return redirect()->route('admin.jenis-hewan.index')
                             ->with('error', 'Gagal menghapus jenis hewan karena masih terkait dengan data lain.');
        }
    }

    // Perbaikan: Tambahkan $id = null ke signature dan definisikan $uniqueRule
    private function validateJenisHewan(Request $request, $id = null) 
    {
        // Definisi $uniqueRule untuk mengecualikan ID saat update
        $uniqueRule = Rule::unique('jenis_hewan', 'nama_jenis_hewan');
        if ($id) {
            $uniqueRule->ignore($id, 'idjenis_hewan');
        }

        return $request->validate([
            'nama_jenis_hewan' => [
                'required',
                'string',
                'max:255',
                'min:3',
                $uniqueRule // Menggunakan $uniqueRule yang sudah didefinisikan
            ],
        ], [
            'nama_jenis_hewan.required' => 'Nama jenis hewan tidak boleh kosong.',
            'nama_jenis_hewan.string' =>'Nama jenis hewan harus berupa teks.',
            'nama_jenis_hewan.max' =>'Nama jenis hewan maksimal 255 karakter.',
            'nama_jenis_hewan.min' =>'Nama jenis hewan minimal 3 karakter.',
            'nama_jenis_hewan.unique' => 'Nama jenis hewan sudah ada.',
        ]);
    }

    // helper untuk membuat data baru
    protected function createJenisHewan(array $data)
    {
        try {
            $jenisHewan = DB::table('jenis_hewan')->insert ([
            'nama_jenis_hewan' => $this->formatNamaJenisHewan($data['nama_jenis_hewan']),
            ]);

        return $jenisHewan;
        } catch (\Exception $e) {
            throw new \Exception('Gagal menyimpan data jenis hewan: ' . $e->getMessage());
        }
    }

    // Helper untuk format nama menjadi Title Case
    protected function formatNamaJenisHewan($nama)
    {
        // Perbaikan: strtolowe seharusnya strtolower
        return trim(ucwords(strtolower($nama))); 
    }    
}

?>