@extends('layouts.app')

@section('title', 'Edit Jenis Hewan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Jenis Hewan</div>

                <div class="card-body">
                    {{-- Menampilkan pesan error dari Session --}}
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Form untuk Submit Data --}}
                    <form action="{{ route('admin.jenis-hewan.update', $jenisHewan->idjenis_hewan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_jenis_hewan" class="form-label">Nama Jenis Hewan <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('nama_jenis_hewan') is-invalid @enderror"
                                id="nama_jenis_hewan"
                                name="nama_jenis_hewan"
                                value="{{ old('nama_jenis_hewan', $jenisHewan->nama_jenis_hewan) }}"
                                placeholder="Masukkan nama jenis hewan"
                                required
                            >

                            @error('nama_jenis_hewan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.jenis-hewan.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection