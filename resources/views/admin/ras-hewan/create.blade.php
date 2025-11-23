@extends('layouts.app')

@section('title', 'Tambah Ras Hewan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Ras Hewan</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.ras-hewan.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_ras" class="form-label">Nama Ras Hewan <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('nama_ras') is-invalid @enderror"
                                id="nama_ras"
                                name="nama_ras"
                                value="{{ old('nama_ras') }}"
                                placeholder="Masukkan nama ras hewan"
                                required
                            >
                            @error('nama_ras')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="idjenis_hewan" class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                            <select class="form-control @error('idjenis_hewan') is-invalid @enderror" id="idjenis_hewan" name="idjenis_hewan" required>
                                <option value="">Pilih Jenis Hewan</option>
                                @foreach ($jenisHewan as $jenis)
                                    <option value="{{ $jenis->idjenis_hewan }}" {{ old('idjenis_hewan') == $jenis->idjenis_hewan ? 'selected' : '' }}>
                                        {{ $jenis->nama_jenis_hewan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idjenis_hewan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.ras-hewan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection