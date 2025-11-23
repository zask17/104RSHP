@extends('layouts.app')

@section('title', 'Tambah Kategori Hewan')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1>Tambah Kategori Hewan</h1>
        
        <a href="{{ route('admin.kategori-hewan.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kategori
        </a>

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.kategori-hewan.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama_kategori">Nama Kategori Hewan <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="nama_kategori"
                    name="nama_kategori"
                    value="{{ old('nama_kategori') }}"
                    placeholder="Masukkan nama kategori hewan"
                    required
                >
                @error('nama_kategori')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>
</div>
@endsection