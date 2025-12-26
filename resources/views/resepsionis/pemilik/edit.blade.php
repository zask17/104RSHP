@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1><i class="fas fa-edit"></i> Edit Pemilik: {{ $pemilik->nama_pemilik }}</h1>
        <a href="{{ route('resepsionis.pemilik.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>

        <form action="{{ route('resepsionis.pemilik.update', $pemilik->idpemilik) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Pemilik <span class="text-danger">*</span></label>
                <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $pemilik->nama_pemilik) }}" required>
            </div>

            <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email', $pemilik->email) }}" required>
            </div>

            <div class="form-group">
                <label>No. WhatsApp <span class="text-danger">*</span></label>
                <input type="text" name="no_wa" value="{{ old('no_wa', $pemilik->no_wa) }}" required>
            </div>

            <div class="form-group">
                <label>Alamat <span class="text-danger">*</span></label>
                <textarea name="alamat" rows="3" required>{{ old('alamat', $pemilik->alamat) }}</textarea>
            </div>

            <button type="submit" class="btn-submit">Perbarui Data Pemilik</button>
        </form>
    </div>
</div>
@endsection