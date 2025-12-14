@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Manajemen Kode Tindakan & Terapi</h1>
        <p>Kelola kode-kode yang digunakan untuk tindakan dan terapi klinis.</p>
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

        {{-- Button Tambah --}}
        <a href="{{ route('admin.kode-tindakan-terapi.create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Tambah Kode Baru
        </a>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Deskripsi Tindakan</th> 
                    <th>Kategori Hewan</th>
                    <th>Kategori Klinis</th>
                    <th>Aksi</th> {{-- PASTIKAN KOLOM INI ADA --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($kodeTindakanTerapi as $item)
                <tr>
                    <td>{{ $item->idkode_tindakan_terapi }}</td>
                    <td><strong>{{ $item->kode }}</strong></td>
                    
                    {{-- PERBAIKAN: Gunakan Str::limit untuk memotong deskripsi --}}
                    <td title="{{ $item->deskripsi_tindakan_terapi }}">
                        {{ Str::limit($item->deskripsi_tindakan_terapi, 100, '...') }} 
                    </td>
                    
                    <td>{{ $item->kategori->nama_kategori ?? 'N/A' }}</td>
                    <td>{{ $item->kategoriKlinis->nama_kategori_klinis ?? 'N/A' }}</td>
                    
                    {{-- KOLOM AKSI --}}
                    <td class="action-buttons">
                        {{-- Button Edit --}}
                        <a href="{{ route('admin.kode-tindakan-terapi.edit', $item->idkode_tindakan_terapi) }}" class="edit-btn">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Form Delete --}}
                        <form action="{{ route('admin.kode-tindakan-terapi.destroy', $item->idkode_tindakan_terapi) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus kode {{ $item->kode }}? Tindakan ini tidak dapat dibatalkan.')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data kode tindakan/terapi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection