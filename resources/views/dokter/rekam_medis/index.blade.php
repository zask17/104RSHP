{{-- resources/views/dokter/rekam_medis/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="page-container">
        {{-- Header dengan Gradient sesuai style.css --}}
        <div class="page-header">
            <h1>Janji Temu & Rekam Medis Saya</h1>
            <p>Kelola daftar kunjungan pasien dan buat catatan rekam medis di sini.</p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabel Data dengan class .data-table dari style.css --}}
        <table class="data-table">
            <thead>
                <tr>
                    <th>No. Urut</th>
                    <th>Tanggal & Waktu Temu</th>
                    <th>Nama Pasien (Pet)</th>
                    <th>Nama Pemilik</th>
                    <th>Status Kunjungan</th>
                    <th>Status Rekam Medis</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($temuDokters as $temu)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $loop->iteration }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($temu->tanggal_temu)->format('d M Y') }} |
                            {{ \Carbon\Carbon::parse($temu->waktu_temu)->format('H:i') }}
                        </td>
                        <td><strong>{{ $temu->nama_pet }}</strong></td>
                        <td>{{ $temu->nama_pemilik }}</td>
                        <td>
                            {{-- Menggunakan badge status yang ada di style.css --}}
                            <span class="status-badge {{ $temu->status == 'Selesai' ? 'status-confirmed' : 'status-pending' }}">
                                {{ $temu->status }}
                            </span>
                        </td>
                        <td>
                            @if ($temu->idrekam_medis)
                                <span class="status-badge status-completed">Tersedia</span>
                            @else
                                <span class="status-badge status-cancelled">Belum Ada</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($temu->idrekam_medis)
                                {{-- Menggunakan style .edit-link (warna oranye) dari style.css --}}
                                <a href="{{ route('dokter.rekam-medis.show', $temu->idrekam_medis) }}" class="edit-link">
                                    <i class="fas fa-file-medical"></i> Lihat RM
                                </a>
                            @else
                                {{-- Menggunakan style link hijau manual atau btn-dashboard --}}
                                <a href="{{ route('dokter.rekam-medis.create', $temu->idreservasi_dokter) }}" class="edit-link"
                                    style="color: #27ae60 !important;">
                                    <i class="fas fa-plus-circle"></i> Buat RM
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada janji temu yang harus Anda tangani saat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Navigasi Paginasi --}}
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $temuDokters->links() }}
        </div>
    </div>
@endsection