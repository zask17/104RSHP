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

            {{-- Nomor HP --}}
            <div class="form-group">
                <label for="no_hp">Nomor HP <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="no_hp"
                    name="no_hp"
                    value="{{ old('no_hp') }}"
                    placeholder="Masukkan nomor telepon aktif"
                    required
                >
                @error('no_hp')
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