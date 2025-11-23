@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1>Edit Role: {{ $role->nama_role }}</h1>
        
        <a href="{{ route('admin.roles.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Role
        </a>

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.roles.update', $role->idrole) }}" method="POST">
            @csrf
            @method('PUT') {{-- Gunakan metode PUT untuk update --}}

            <div class="form-group">
                <label for="nama_role">Nama Role <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="nama_role"
                    name="nama_role"
                    value="{{ old('nama_role', $role->nama_role) }}"
                    placeholder="Contoh: Dokter, Perawat, Resepsionis"
                    required
                >
                @error('nama_role')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Perbarui Role
            </button>
        </form>
    </div>
</div>
@endsection