@extends('layouts.app')

@section('content')
    <div class="page-container">
        <div class="page-header">
            <h1><i class="fas fa-clipboard-list"></i> Antrean Pendaftaran (Temu Dokter)</h1>
            <p>Daftar pasien yang dijadwalkan untuk konsultasi pada tanggal {{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</p>
        </div>

        <div class="main-content">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="info-box">
                <i class="fas fa-info-circle"></i> Data ini menampilkan antrean janji temu yang berstatus 'Pending' atau 'Dikonfirmasi' pada hari ini.
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Urut</th>
                        <th>Waktu Temu</th>
                        <th>Status</th>
                        <th>Nama Pasien (Pet)</th>
                        <th>Nama Pemilik</th>
                        <th>Dokter Bertugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendaftarans as $pendaftaran)
                        <tr>
                            <td>**{{ $pendaftaran->no_urut }}**</td>
                            <td>{{ \Carbon\Carbon::parse($pendaftaran->waktu_temu)->format('H:i') }} WIB</td>
                            <td>
                                @php
                                    $statusClass = ($pendaftaran->status == 'Dikonfirmasi') ? 'status-confirmed' : 'status-pending';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $pendaftaran->status }}</span>
                            </td>
                            <td>{{ $pendaftaran->pet->nama ?? 'N/A' }}</td>
                            <td>{{ $pendaftaran->pet->pemilik->nama_pemilik ?? 'N/A' }}</td>
                            {{-- Akses nama dokter melalui relasi RoleUser -> User --}}
                            <td>{{ $pendaftaran->roleUser->user->nama ?? 'N/A' }}</td>
                            <td class="action-buttons">
                                {{-- Link Edit mengarah ke Edit Temu Dokter --}}
                                <a href="{{ route('resepsionis.temu-dokter.edit', $pendaftaran->idreservasi_dokter) }}" class="edit-btn">
                                    <i class="fas fa-edit"></i> Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">Tidak ada pasien dalam antrean pendaftaran hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Styling dasar untuk status badge */
    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8em;
        font-weight: bold;
        color: white;
    }
    .status-pending { background-color: #f39c12; }
    .status-confirmed { background-color: #2ecc71; }
</style>
@endpush