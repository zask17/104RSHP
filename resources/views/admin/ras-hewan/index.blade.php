@extends('layouts.app')

@section('content')
<div class="page-container">
    {{-- Memastikan judul di luar page-header agar menyerupai tampilan foto --}}
    <div class="page-header-title-only">
        <h1>Manajemen Ras Hewan</h1>
    </div>

    <div class="main-content">
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
        
        {{-- Menghilangkan tombol Tambah Ras Hewan karena di foto input ada di dalam tabel --}}

        {{-- Menggunakan tabel baru untuk layout pengelompokan --}}
        <table class="ras-hewan-grouped-table">
            <thead>
                {{-- Header biru sesuai foto --}}
                <tr class="header-row-grouped">
                    <th class="col-jenis">Jenis Hewan</th>
                    <th class="col-ras">Ras yang Terdaftar</th>
                </tr>
            </thead>
            <tbody>
                {{-- Perulangan utama untuk setiap kelompok Jenis Hewan --}}
                @forelse ($groupedRasHewan as $jenisNama => $rasCollection)
                    @php
                        // Menghitung jumlah baris untuk Jenis Hewan: 
                        // Jumlah ras + 1 baris untuk input "Tambah Ras"
                        $rowCount = $rasCollection->count() + 1;
                        
                        // Ambil detail Jenis Hewan (asumsi semua item di collection memiliki 'jenis' yang sama)
                        $jenisDetail = $rasCollection->first()->jenis ?? null;
                        
                        // Menyiapkan Nama Jenis Hewan dengan Nama Latin (sesuai foto)
                        $namaLengkap = $jenisNama;
                        if ($jenisDetail && $jenisDetail->nama_latin) {
                            $namaLengkap .= ' (' . $jenisDetail->nama_latin . ')';
                        }
                    @endphp

                    {{-- Baris pertama untuk Jenis Hewan --}}
                    <tr>
                        {{-- Kolom Jenis Hewan dengan rowspan --}}
                        <td class="jenis-hewan-cell" rowspan="{{ $rowCount }}">
                            <strong>{{ $namaLengkap }}</strong>
                        </td>
                        
                        {{-- Menampilkan semua ras satu per satu --}}
                        @foreach ($rasCollection->sortBy('nama_ras') as $index => $ras)
                            {{-- Hanya baris pertama yang digabung dengan kolom Jenis Hewan di atas --}}
                            @if ($index === 0)
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
                            </tr>
                            @else
                            {{-- Baris ras berikutnya untuk Jenis Hewan yang sama --}}
                            <tr>
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
                            </tr>
                            @endif
                        @endforeach
                        
                        {{-- Baris Tambah Ras Baru (Input field) --}}
                    <tr>
                        <td class="ras-hewan-item add-new-row">
                            {{-- Form Sederhana untuk Tambah Ras Baru --}}
                            <form action="{{ route('admin.ras-hewan.store') }}" method="POST" class="inline-add-form">
                                @csrf
                                {{-- Hidden input untuk jenis hewan ID yang diambil dari jenisDetail --}}
                                <input type="hidden" name="idjenis_hewan" value="{{ $jenisDetail->idjenis_hewan ?? '' }}"> 
                                <input type="text" name="nama_ras" placeholder="Nama ras baru" required>
                                <button type="submit" class="add-ras-btn">Tambah Ras</button>
                            </form>
                        </td>
                    </tr>
                    
                @empty
                    <tr>
                        <td colspan="2" style="text-align: center;">Tidak ada data jenis atau ras hewan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection