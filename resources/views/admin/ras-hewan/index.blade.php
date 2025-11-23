@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Manajemen Ras Hewan</h1>
        <p>Kelola berbagai ras dari setiap jenis hewan.</p>
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

        <a href="{{ route('admin.ras-hewan.create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Tambah Ras Hewan
        </a>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Ras</th>
                    <th>Jenis Hewan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rasHewan as $ras)
                <tr>
                    <td>{{ $ras->idras_hewan }}</td>
                    <td>{{ $ras->nama_ras }}</td>
                    <td>{{ $ras->jenis->nama_jenis_hewan ?? 'N/A' }}</td>
                    <td class="action-buttons">
                        <a href="{{ route('admin.ras-hewan.edit', $ras->idras_hewan) }}" class="edit-btn">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <form action="{{ route('admin.ras-hewan.destroy', $ras->idras_hewan) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus ras {{ $ras->nama_ras }}? Tindakan ini tidak dapat dibatalkan.')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data ras hewan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection