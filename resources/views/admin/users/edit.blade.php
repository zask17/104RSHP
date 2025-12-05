@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1>Edit User: {{ $user->name }}</h1>
        
        <a href="{{ route('admin.users.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar User
        </a>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Gunakan metode PUT untuk update --}}

            {{-- Nama --}}
            <div class="form-group">
                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    placeholder="Masukkan nama lengkap"
                    required
                >
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    placeholder="Masukkan alamat email"
                    required
                >
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Role --}}
            <div class="form-group">
                <label for="idrole">Role/Jabatan <span class="text-danger">*</span></label>
                <select id="idrole" name="idrole" required>
                    <option value="">Pilih Role</option>
                    @foreach ($roles as $role)
                        <option 
                            value="{{ $role->idrole }}" 
                            {{ old('idrole', $user->idrole) == $role->idrole ? 'selected' : '' }}
                        >
                            {{ $role->nama_role }}
                        </option>
                    @endforeach
                </select>
                @error('idrole')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="info-box">
                <h3>Ubah Password</h3>
                <p>Kosongkan field di bawah jika Anda tidak ingin mengganti password user ini.</p>
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password">Password Baru</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                >
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password baru"
                >
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Perbarui User
            </button>
        </form>
    </div>
</div>
@endsection