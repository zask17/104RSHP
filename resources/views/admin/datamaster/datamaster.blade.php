@extends('layouts.app')

@section('title', 'Data Master - RSHP UNAIR')

@section('content')

    <div class="page-container">
        <div class="page-header">
            <h1>Data Master</h1>
            <p>Pilih salah satu menu di bawah untuk mengelola data sistem.</p>
        </div>


        <div class="dashboard-container" style="display: flex; gap: 30px; align-items: flex-start;">

            <main style="flex: 1; min-width: 0;">
                <div class="main-dashboard-content">
                    <div class="welcome-section">
                        <h1>Selamat Datang, {{ session('user_name', 'Admin') }}!</h1>
                        <p>Anda login sebagai <strong>{{ session('user_role_name', 'Administrator') }}</strong>. Silakan
                            kelola data master melalui menu di bawah ini.</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="dashboard-grid">
                        <a href="{{ route('jenis-hewan.index') }}" class="dashboard-card">
                            <h3><i class="fas fa-paw"></i> Jenis Hewan</h3>
                            <p>Kelola kategori utama hewan.</p>
                        </a>
                        <a href="{{ route('ras-hewan.index') }}" class="dashboard-card">
                            <h3><i class="fas fa-dog"></i> Ras Hewan</h3>
                            <p>Kelola berbagai ras dari setiap jenis hewan.</p>
                        </a>
                        <a href="{{ route('kategori-hewan.index') }}" class="dashboard-card">
                            <h3><i class="fas fa-tags"></i> Kategori Hewan</h3>
                            <p>Kelola kategori umum untuk hewan.</p>
                        </a>

                        <a href="{{ route('kategori-klinis.index') }}" class="dashboard-card">
                            <h3><i class="fas fa-stethoscope"></i> Kategori Klinis</h3>
                            <p>Kelola kategori untuk keperluan klinis.</p>
                        </a>
                        <a href="{{ route('kode-tindakan-terapi.index') }}" class="dashboard-card">
                            <h3><i class="fas fa-notes-medical"></i> Kode Tindakan</h3>
                            <p>Kelola kode untuk tindakan dan terapi.</p>
                        </a>

                        <a href="{{ route('users.index') }}" class="dashboard-card">
                            <h3><i class="fas fa-users-cog"></i> Manajemen User</h3>
                            <p>Kelola akun pengguna sistem.</p>
                        </a>
                        <a href="{{ route('roles.index') }}" class="dashboard-card">
                            <h3><i class="fas fa-user-shield"></i> Manajemen Role</h3>
                            <p>Kelola hak akses dan peran pengguna.</p>
                        </a>
                        <a href="{{ route('pemilik.index') }}" class="dashboard-card">
                            <h3><i class="fas fa-user-friends"></i> Data Pemilik</h3>
                            <p>Kelola data pemilik hewan peliharaan.</p>
                        </a>
                        <a href="{{ route('pets.index') }}" class="dashboard-card">
                            <h3><i class="fas fa-cat"></i> Data Pasien (Pets)</h3>
                            <p>Lihat dan kelola data semua pasien.</p>
                        </a>
                    </div>

                    <form id="logout-form-dashboard" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </main>
        </div>
        {{-- <div class="nav-grid">
            <a href="{{ route('admin.datamaster.user') }}" class="nav-card">
                <h3>Data User</h3>
                <p>Kelola data, peran, dan kata sandi pengguna sistem.</p>
            </a> --}}

            {{-- Tambahkan tautan-tautan sub-menu lainnya menggunakan route('admin.datamaster.nama_rute') --}}
        </div>
    </div>
@endsection