@extends('layouts.app')

@section('title', 'Edit Kode Tindakan/Terapi')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1>Edit Kode Tindakan/Terapi: {{ $kodeTindakanTerapi->kode }}</h1>
        
        <a href="{{ route('admin.kode-tindakan-terapi.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kode
        </a>

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.kode-tindakan-terapi.update', $kodeTindakanTerapi->idkode_tindakan_terapi) }}" method="POST">
            @csrf
            @method('PUT') {{-- Gunakan metode PUT untuk update --}}

            {{-- Form Group: Kode --}}
            <div class="form-group">
                <label for="kode">Kode Tindakan/Terapi <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="kode"
                    name="kode"
                    value="{{ old('kode', $kodeTindakanTerapi->kode) }}"
                    placeholder="Contoh: RX001"
                    required
                >
                @error('kode')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Form Group: Kategori Hewan --}}
            <div class="form-group">
                <label for="idkategori">Kategori Hewan <span class="text-danger">*</span></label>
                <select id="idkategori" name="idkategori" required>
                    <option value="">Pilih Kategori Hewan</option>
                    @foreach ($kategori as $item)
                        <option 
                            value="{{ $item->idkategori }}" 
                            {{ old('idkategori', $kodeTindakanTerapi->idkategori) == $item->idkategori ? 'selected' : '' }}
                        >
                            {{ $item->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('idkategori')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Form Group: Kategori Klinis --}}
            <div class="form-group">
                <label for="idkategori_klinis">Kategori Klinis <span class="text-danger">*</span></label>
                <select id="idkategori_klinis" name="idkategori_klinis" required>
                    <option value="">Pilih Kategori Klinis</option>
                    @foreach ($kategoriKlinis as $item)
                        <option 
                            value="{{ $item->idkategori_klinis }}" 
                            {{ old('idkategori_klinis', $kodeTindakanTerapi->idkategori_klinis) == $item->idkategori_klinis ? 'selected' : '' }}
                        >
                            {{ $item->nama_kategori_klinis }}
                        </option>
                    @endforeach
                </select>
                @error('idkategori_klinis')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Form Group: Deskripsi --}}
            <div class="form-group">
                <label for="deskripsi">Deskripsi Tindakan <span class="text-danger">*</span></label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="3"
                    placeholder="Jelaskan secara singkat tindakan/terapi ini"
                    required
                >{{ old('deskripsi', $kodeTindakanTerapi->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Perbarui Kode
            </button>
        </form>
    </div>
</div>
@endsection