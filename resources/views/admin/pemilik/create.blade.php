@extends('layouts.app')

@section('title', 'Tambah Data Pemilik')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1>Tambah Data Pemilik</h1>
        
        <a href="{{ route('admin.pemilik.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pemilik
        </a>

        <form action="{{ route('admin.pemilik.store') }}" method="POST">
            @csrf

            {{-- Nama Pemilik --}}
            <div class="form-group">
                <label for="nama_pemilik">Nama Pemilik <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="nama_pemilik"
                    name="nama_pemilik"
                    value="{{ old('nama_pemilik') }}"
                    placeholder="Masukkan nama lengkap pemilik"
                    required
                >
                @error('nama_pemilik')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nomor WhatsApp --}}
            <div class="form-group">
                <label for="no_wa">Nomor WhatsApp <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="no_wa"
                    name="no_wa"
                    value="{{ old('no_wa') }}"
                    placeholder="Masukkan nomor WhatsApp aktif"
                    required
                >
                @error('no_wa')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- Email (Optional) --}}
            <div class="form-group">
                <label for="email">Email (Opsional)</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan alamat email (jika ada)"
                >
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- Password BARU DITAMBAHKAN --}}
            <div class="form-group">
                <label for="password">Password <span class="text-danger">*</span></label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password untuk login (min. 6 karakter)"
                    required
                >
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Alamat --}}
            <div class="form-group">
                <label for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea
                    id="alamat"
                    name="alamat"
                    rows="3"
                    placeholder="Masukkan alamat lengkap pemilik"
                    required
                >{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Data Pemilik
            </button>
        </form>
    </div>
</div>
@endsection