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
                    {{-- <th>Role</th> --}}
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    {{-- Menggunakan iduser sebagai primary key --}}
                    <td>{{ $user->iduser }}</td> 
                    
                    {{-- Menggunakan nama sebagai nama field --}}
                    <td>{{ $user->nama }}</td> 
                    <td>{{ $user->email }}</td>
                    {{-- <td>{{ $user->RoleUser->nama_role ?? 'N/A' }}</td> --}}
                    <td class="action-buttons">
                        
                        {{-- Diperbaiki: Menggunakan $user->iduser sebagai parameter rute --}}
                        <a href="{{ route('admin.users.edit', $user->iduser) }}" class="edit-btn">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Diperbaiki: Menggunakan $user->iduser sebagai parameter rute --}}
                        <form action="{{ route('admin.users.destroy', $user->iduser) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            {{-- Menonaktifkan tombol hapus jika user mencoba menghapus dirinya sendiri --}}
                            <button 
                                type="submit" 
                                class="delete-btn" 
                                onclick="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')" 
                                {{ auth()->id() == $user->iduser ? 'disabled' : '' }}
                            >
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