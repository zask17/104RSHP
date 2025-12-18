@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="page-header">
            <h1>Profil Perawat</h1>
            <p>Data lengkap akun dan informasi profesional Anda</p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card" style="text-align: left;">
                <h3><i class="fas fa-id-card"></i> Informasi Akun</h3>
                <hr>
                <p><strong>Nama Lengkap:</strong> {{ $userProfile->nama }}</p>
                <p><strong>Email:</strong> {{ $userProfile->email }}</p>
                <p><strong>Pendidikan:</strong> {{ $userProfile->pendidikan ?? '-' }}</p>
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

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('perawat.dashboard') }}" class="btn-dashboard">Kembali ke Beranda</a>
        </div>
    </div>
@endsection