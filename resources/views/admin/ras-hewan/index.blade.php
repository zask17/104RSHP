@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Manajemen Ras Hewan</h1>
    </div>

    <div class="main-content">
        {{-- Menampilkan Flash Message (Success/Error) --}}
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- TOMBOL TAMBAH STANDALONE DIHAPUS --}}
        
        {{-- Tabel pengelompokan --}}
        <table class="ras-hewan-grouped-table">
            <thead>
                <tr class="header-row-grouped">
                    <th class="col-jenis">Jenis Hewan</th>
                    <th class="col-ras">Ras yang Terdaftar</th>
                </tr>
            </thead>
            <tbody>
                {{-- Perulangan Utama berdasarkan Jenis Hewan --}}
                @forelse ($jenisHewanWithRas as $jenis)
                    @php
                        // Hitung jumlah ras (bisa 0)
                        $rasCollection = $jenis->ras;
                        $rasCount = $rasCollection->count();
                        
                        // Jumlah baris adalah jumlah ras + 1 (untuk form tambah)
                        $rowCount = max(1, $rasCount) + 1; // Pastikan minimal 2 baris (1 untuk ras, 1 untuk form)
                        
                        // Menyiapkan Nama Jenis Hewan
                        $namaLengkap = $jenis->nama_jenis_hewan;
                        $hasRas = $rasCount > 0;
                    @endphp

                    <tr>
                        {{-- Kolom Jenis Hewan dengan rowspan --}}
                        <td class="jenis-hewan-cell" rowspan="{{ $rowCount }}">
                            <strong>{{ $namaLengkap }}</strong>
                        </td>
                        
                        @if ($hasRas)
                            {{-- Menampilkan semua ras satu per satu --}}
                            @foreach ($rasCollection as $index => $ras)
                                {{-- Baris ras berikutnya --}}
                                @if ($index > 0) <tr> @endif
                                
                                <td class="ras-hewan-item">
                                    {{ $ras->nama_ras }}
                                    <div class="action-links-inline">
                                        <a href="{{ route('admin.ras-hewan.edit', $ras->idras_hewan) }}" class="edit-link">edit</a>
                                        <form action="{{ route('admin.ras-hewan.destroy', $ras->idras_hewan) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-link" onclick="return confirm('Hapus ras {{ $ras->nama_ras }}?')">hapus</button>
                                        </form>
                                    </div>
                                </td>
                                
                                @if ($index > 0) </tr> @endif
                                
                            @endforeach
                            
                        @else
                            {{-- Jika tidak ada ras, baris pertama kolom ras menampilkan pesan --}}
                            <td class="ras-hewan-item">
                                <span class="text-muted">Belum ada ras terdaftar.</span>
                            </td>
                        </tr>
                        @endif
                        
                    {{-- Baris Tambah Ras Baru (Selalu ditampilkan) --}}
                    <tr>
                        <td class="ras-hewan-item add-new-row">
                            {{-- Form Sederhana untuk Tambah Ras Baru (Inline) --}}
                            <form action="{{ route('admin.ras-hewan.store') }}" method="POST" class="inline-add-form">
                                @csrf
                                {{-- Hidden input menggunakan ID jenis hewan saat ini --}}
                                <input type="hidden" name="idjenis_hewan" value="{{ $jenis->idjenis_hewan }}"> 
                                <input type="text" name="nama_ras" placeholder="Nama ras baru" required>
                                <button type="submit" class="add-ras-btn">Tambah Ras</button>
                            </form>
                            {{-- Menampilkan error untuk form inline terakhir yang disubmit --}}
                            @error('nama_ras')
                                @if (old('idjenis_hewan') == $jenis->idjenis_hewan)
                                    <div class="invalid-feedback d-block text-danger" style="font-size: 0.9em;">{{ $message }}</div>
                                @endif
                            @enderror
                        </td>
                    </tr>
                    
                @empty
                    <tr>
                        <td colspan="2" style="text-align: center;">Tidak ada data jenis hewan terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection