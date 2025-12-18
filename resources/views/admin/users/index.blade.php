@extends('layouts.app')

@section('content')
    <div class="page-container">
        <div class="page-header">
            <h1>Manajemen User</h1>
            <p>Kelola data dan hak akses pengguna sistem.</p>
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
            <a href="{{ route('admin.users.create') }}" class="add-btn">
                <i class="fas fa-user-plus"></i> Tambah User Baru
            </a>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->iduser }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role)
                                    {{ $user->role->nama_role }}
                                @else
                                    <span class="text-danger">N/A</span>
                                @endif
                            </td>
                            {{-- Cari bagian kolom AKSI di dalam tabel --}}
                            <td class="action-buttons">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.users.edit', $user->iduser) }}" class="edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                {{-- Form Hapus --}}
                                <form action="{{ route('admin.users.destroy', $user->iduser) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE') {{-- Laravel memerlukan directive ini untuk route jenis DELETE --}}

                                    <button type="submit" class="delete-btn"
                                        style="background-color: #e3342f; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->nama }}? Semua akses role user ini juga akan dihapus.')"
                                        {{ Auth::id() == $user->iduser ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada data user yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection