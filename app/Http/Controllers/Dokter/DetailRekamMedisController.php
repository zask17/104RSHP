<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailRekamMedisController extends Controller
{
    /**
     * CREATE: Menyimpan detail tindakan baru
     */
    public function store(Request $request, $idrekam_medis)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string|max:1000',
        ]);

        DB::table('detail_rekam_medis')->insert([
            'idrekam_medis' => $idrekam_medis,
            'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
            'detail' => $request->detail,
        ]);

        return redirect()->back()->with('success', 'Detail tindakan/terapi berhasil ditambahkan.');
    }

    /**
     * UPDATE: Mengubah detail tindakan yang sudah ada
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string|max:1000',
        ]);

        DB::table('detail_rekam_medis')
            ->where('iddetail_rekam_medis', $id)
            ->update([
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail,
            ]);

        return redirect()->back()->with('success', 'Detail tindakan berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus detail tindakan
     */
    public function destroy($id)
    {
        DB::table('detail_rekam_medis')
            ->where('iddetail_rekam_medis', $id)
            ->delete();

        return redirect()->back()->with('success', 'Detail tindakan berhasil dihapus.');
    }
}