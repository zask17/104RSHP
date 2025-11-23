@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Manajemen Data Pasien (Pets)</h1>
        <p>Kelola data lengkap hewan peliharaan yang terdaftar.</p>
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
        <a href="{{ route('admin.pets.create') }}" class="add-btn">
            <i class="fas fa-paw"></i> Tambah Pasien Baru
        </a>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pasien</th>
                    <th>Pemilik</th>
                    <th>Jenis</th>
                    <th>Ras</th>
                    <th>Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pets as $pet)
                <tr>
                    <td>{{ $pet->idpet }}</td>
                    <td><strong>{{ $pet->nama_pet }}</strong></td>
                    <td>{{ $pet->pemilik->nama_pemilik ?? 'N/A' }}</td>
                    <td>{{ $pet->jenisHewan->nama_jenis_hewan ?? 'N/A' }}</td>
                    <td>{{ $pet->rasHewan->nama_ras ?? 'N/A' }}</td>
                    <td>{{ $pet->jenis_kelamin }}</td>
                    <td>{{ $pet->tanggal_lahir }}</td>
                    <td class="action-buttons">
                        {{-- Button Edit --}}
                        <a href="{{ route('admin.pets.edit', $pet->idpet) }}" class="edit-btn">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        {{-- Form Delete --}}
                        <form action="{{ route('admin.pets.destroy', $pet->idpet) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus data pasien {{ $pet->nama_pet }}? Tindakan ini tidak dapat dibatalkan jika ada rekam medis terkait.')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data pasien yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection