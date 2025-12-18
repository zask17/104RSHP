@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="form-container">
        <h2 class="form-title-text">Edit Profil Dokter</h2>
        
        <form action="{{ route('dokter.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="{{ $user->nama }}" required>
            </div>

            <div class="form-group">
                <label>Bidang Dokter</label>
                <input type="text" name="bidang_dokter" value="{{ $dokter->bidang_dokter ?? '' }}">
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin">
                    <option value="L" {{ ($dokter->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ ($dokter->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" value="{{ $dokter->no_hp ?? '' }}">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat">{{ $dokter->alamat ?? '' }}</textarea>
            </div>

            <button type="submit" class="btn-submit">Update Profil</button>
            <a href="{{ route('dokter.profile.index') }}" class="btn-dashboard btn-logout" style="display:block; text-align:center; margin-top:10px;">Batal</a>
        </form>
    </div>
</div>
@endsection