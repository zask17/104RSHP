@extends('layouts.app')

@section('title', 'Tambah Kategori Klinis')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1>Tambah Kategori Klinis</h1>
        
        <a href="{{ route('admin.kategori-klinis.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kategori Klinis
        </a>

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.kategori-klinis.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama_kategori_klinis">Nama Kategori Klinis <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="nama_kategori_klinis"
                    name="nama_kategori_klinis"
                    value="{{ old('nama_kategori_klinis') }}"
                    placeholder="Masukkan nama kategori klinis"
                    required
                >
                @error('nama_kategori_klinis')
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