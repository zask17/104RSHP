@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="page-header">
            <h1>Profil Dokter</h1>
            <p>Data lengkap akun dan performa klinis Anda</p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card" style="text-align: left;">
                <h3><i class="fas fa-id-card"></i> Informasi Akun</h3>
                <hr>
                <p><strong>Nama Lengkap:</strong> {{ $userProfile->nama }}</p>
                <p><strong>Email:</strong> {{ $userProfile->email }}</p>
                <p><strong>Spesialisasi:</strong> {{ $userProfile->bidang_dokter ?? 'Dokter Umum' }}</p>
                <p><strong>Jabatan:</strong> {{ $userProfile->nama_role }}</p>
                <p><strong>Status:</strong>
                    <span
                        class="status-badge {{ $userProfile->status_role == 1 ? 'status-confirmed' : 'status-cancelled' }}">
                        {{ $userProfile->status_role == 1 ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </p>
            </div>

            <div class="dashboard-card" style="text-align: left;">
                <h3><i class="fas fa-user-circle"></i> Detail Kontak</h3>
                <hr>
                <p><strong>Jenis Kelamin:</strong> {{ $userProfile->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                <p><strong>No. WhatsApp:</strong> {{ $userProfile->no_hp ?? '-' }}</p>
                <p><strong>Alamat:</strong> {{ $userProfile->alamat ?? '-' }}</p>
            </div>
        </div>


        <div style="margin-top: 20px;">
            <a href="{{ route('dokter.profile.edit') }}" class="btn-update"
                style="text-decoration: none; padding: 10px 20px; background-color: #f39c12; color: white; border-radius: 5px;">
                <i class="fas fa-edit"></i> Edit Profil
            </a>
        </div>


        <div class="dashboard-card" style="margin-top: 20px; background: #eaf5fb;">
            <h3>Statistik Pelayanan</h3>
            <div class="content-wrapper" style="margin-bottom: 0; padding: 20px;">
                <div class="left-section">
                    <div style="font-size: 3rem; font-weight: bold; color: #1e3c72;">
                        {{ number_format($jumlah_rekam_medis) }}
                    </div>
                    <p style="font-size: 1.1rem; color: #333;">Total Pasien Ditangani (Rekam Medis)</p>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/dokter/dashboard') }}" class="btn-dashboard">Kembali ke Beranda</a>
        </div>
    </div>
@endsection