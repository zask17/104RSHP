@extends('layouts.app')

@section('content')


    <main style="flex: 1; min-width: 0;">
        <div class="main-dashboard-content">
            <div class="welcome-section">
                <h1>Selamat Datang, {{ session('user_name', 'Admin') }}!</h1>
                <p>Anda login sebagai <strong>{{ session('user_role_name', 'Administrator') }}</strong>. Silakan kelola data
                    pasien melalui menu di bawah ini.</p>
                {{-- <a href="{{ route('logout') }}" class="btn-dashboard btn-logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form-dashboard').submit();">
                    Logout
                </a> --}}
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="dashboard-grid">
                <a href="{{ route('resepsionis.pendaftaran') }}" class="dashboard-card">
                    <h3><i class="fas fa-paw"></i> Daftar Pendaftaran</h3>
                    <p>Lihat antrean pendaftaran dan mulai registrasi pasien baru.</p>
                </a>

                {{-- Arahkan ke CRUD Temu Dokter --}}
                <a href="{{ route('resepsionis.temu-dokter.index') }}" class="dashboard-card">
                    <h3><i class="fas fa-dog"></i> Kelola Temu Dokter</h3>
                    <p>Jadwalkan, lihat, dan kelola pertemuan dengan dokter.</p>
                </a>

                {{-- Arahkan ke CRUD Pets (Pasien) --}}
                <a href="{{ route('resepsionis.pets.index') }}" class="dashboard-card">
                    <h3><i class="fas fa-cat"></i> Data Pasien (Pets)</h3>
                    <p>Kelola data semua pasien yang terdaftar.</p>
                </a>

                {{-- Arahkan ke CRUD Pemilik --}}
                <a href="{{ route('resepsionis.pemilik.index') }}" class="dashboard-card">
                    <h3><i class="fas fa-user-friends"></i> Data Pemilik</h3>
                    <p>Kelola data pemilik hewan peliharaan.</p>
                </a>
            </div>

            <form id="logout-form-dashboard" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </main>
    </div>

@endsection