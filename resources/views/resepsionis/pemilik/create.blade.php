@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1><i class="fas fa-user-plus"></i> Tambah Pemilik Baru</h1>
        <a href="{{ route('resepsionis.pemilik.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Kembali</a>

        <form action="{{ route('resepsionis.pemilik.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Pemilik <span class="text-danger">*</span></label>
                <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik') }}" required>
            </div>

            <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Password Akun <span class="text-danger">*</span></label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter">
            </div>

            <div class="form-group">
                <label>No. WhatsApp <span class="text-danger">*</span></label>
                <input type="text" name="no_wa" value="{{ old('no_wa') }}" required placeholder="Contoh: 08123456789">
            </div>

            <div class="form-group">
                <label>Alamat <span class="text-danger">*</span></label>
                <textarea name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
            </div>

            <button type="submit" class="btn-submit">Simpan Data Pemilik</button>
        </form>
    </div>
</div>
@endsection