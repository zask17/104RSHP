@extends('layouts.app') 

@push('styles')
<style>
    .action-buttons form {
        display: inline-block;
    }
    .action-buttons .edit-btn,
    .action-buttons .delete-btn {
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        font-weight: bold;
        margin-right: 5px;
        display: inline-block;
        transition: all 0.3s ease;
    }
</style>
@endpush

@section('title', 'Daftar Jenis Hewan')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Manajemen Jenis Hewan</h1>
        <p>Kelola data master untuk kategori utama hewan.</p>
    </div>

    {{-- Menampilkan Flash Message (Success/Error) --}}
    @if (session('success'))
        <div class="alert alert-success" style="margin-top: 20px;">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" style="margin-top: 20px;">{{ session('error') }}</div>
    @endif

    {{-- Tombol Tambah --}}
    <a href="{{ route('admin.jenis-hewan.create') }}" class="add-btn">
        Tambah Jenis Hewan
    </a>

    {{-- Tabel Data --}}
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Jenis Hewan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop untuk menampilkan data dari Controller --}}
            @foreach ($jenisHewan as $index => $hewan)
            <tr>
                {{-- Nomor Urut (index + 1) --}}
                <td>{{ $index + 1 }}</td> 
                
                {{-- Nama Jenis Hewan --}}
                <td>{{ $hewan->nama_jenis_hewan }}</td>
                
                {{-- Kolom Aksi --}}
                <td class="action-buttons">
                    {{-- Link Edit --}}
                    <a href="{{ route('admin.jenis-hewan.edit', $hewan->idjenis_hewan) }}" class="edit-btn">
                        Edit
                    </a>
                    
                    {{-- Link Hapus (Delete Form) --}}
                    {{-- Anda akan menggunakan form POST untuk DELETE di Laravel --}}
                    <form action="{{ route('admin.jenis-hewan.destroy', $hewan->idjenis_hewan) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus jenis hewan ini?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
            
            @if ($jenisHewan->isEmpty())
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data jenis hewan.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection