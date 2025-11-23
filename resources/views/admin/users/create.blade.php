@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Manajemen Role (Jabatan)</h1>
        <p>Kelola daftar role pengguna yang terdaftar di sistem.</p>
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
        <a href="{{ route('admin.roles.create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Tambah Role Baru
        </a>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $item)
                <tr>
                    <td>{{ $item->idrole }}</td>
                    <td>{{ $item->nama_role }}</td>
                    <td class="action-buttons">
                        {{-- Button Edit --}}
                        <a href="{{ route('admin.roles.edit', $item->idrole) }}" class="edit-btn">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Form Delete --}}
                        <form action="{{ route('admin.roles.destroy', $item->idrole) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus role {{ $item->nama_role }}? Tindakan ini tidak dapat dibatalkan jika masih ada user terkait.')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data role yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection