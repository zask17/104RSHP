{{-- resources/views/dokter/rekam_medis/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="page-container">
    {{-- Header dengan Gradient sesuai style.css --}}
    <div class="page-header">
        <h1>Buat Rekam Medis Baru</h1>
        <p>Silakan isi hasil pemeriksaan untuk melanjutkan ke pengisian detail tindakan.</p>
    </div>

    <a href="{{ route('dokter.rekam-medis.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Janji Temu
    </a>

    {{-- Ringkasan Info Pasien dari Query Builder --}}
    <div class="dashboard-card" style="text-align: left; background-color: #eaf5fb; border-left: 5px solid #6588e8; margin-bottom: 30px;">
        <div style="padding: 10px;">
            {{-- FIX: Mengakses nama_pet dan nama_pemilik langsung sesuai Query Controller --}}
            <p><strong>Pasien:</strong> {{ $temuDokter->nama_pet }}</p>
            <p><strong>Pemilik:</strong> {{ $temuDokter->nama_pemilik ?? '-' }}</p>
            <p><strong>Janji Temu:</strong> {{ \Carbon\Carbon::parse($temuDokter->tanggal_temu)->format('d F Y') }} pukul {{ \Carbon\Carbon::parse($temuDokter->waktu_temu)->format('H:i') }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Container sesuai style.css --}}
    <div class="form-container">
        <form action="{{ route('dokter.rekam-medis.store') }}" method="POST">
            @csrf
            {{-- Hidden ID Janji Temu --}}
            <input type="hidden" name="idreservasi_dokter" value="{{ $temuDokter->idreservasi_dokter }}">

            <div class="form-group">
                <label for="anamnesa">Anamnesa (Riwayat Pasien)</label>
                <textarea name="anamnesa" id="anamnesa" rows="4" placeholder="Keluhan pemilik, riwayat alergi, dll..." required>{{ old('anamnesa') }}</textarea>
            </div>

            <div class="form-group">
                <label for="temuan_klinis">Temuan Klinis (Hasil Pemeriksaan Fisik/Penunjang)</label>
                <textarea name="temuan_klinis" id="temuan_klinis" rows="4" placeholder="Suhu tubuh, berat badan, kondisi fisik, dll..." required>{{ old('temuan_klinis') }}</textarea>
            </div>

            <div class="form-group">
                <label for="diagnosa">Diagnosa</label>
                <textarea name="diagnosa" id="diagnosa" rows="3" placeholder="Diagnosa dokter..." required>{{ old('diagnosa') }}</textarea>
            </div>

            <button type="submit" class="btn-submit" style="margin-top: 20px;">
                <i class="fas fa-save"></i> Buat Rekam Medis & Lanjut ke Tindakan
            </button>
        </form>
    </div>
</div>
@endsection