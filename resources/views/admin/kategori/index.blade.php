@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Manajemen Kategori Hewan</h1>
        <p>Kelola kategori umum untuk hewan peliharaan.</p>
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
        <a href="{{ route('admin.kategori-hewan.create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>

        <table class="data-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategori as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_kategori }}</td>
                    <td class="action-buttons">
                        {{-- Button Edit --}}
                        <a href="{{ route('admin.kategori-hewan.edit', $item->idkategori) }}" class="edit-btn">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Form Delete --}}
                        <form action="{{ route('admin.kategori-hewan.destroy', $item->idkategori) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $item->nama_kategori }}? Tindakan ini tidak dapat dibatalkan jika masih ada relasi data.')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data kategori hewan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection