@extends('layouts.app')

@section('content')
    <div class="page-container">
        <div class="page-header">
            <h1><i class="fas fa-clipboard-list"></i> Antrean Pendaftaran Hari Ini</h1>
            <p>Daftar pasien yang registrasi (walk-in) pada tanggal {{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}.</p>
        </div>

        <div class="main-content">
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
            
            {{-- Tombol Tambah diarahkan ke create Pendaftaran --}}
            <a href="{{ route('resepsionis.pendaftaran.create') }}" class="add-btn">
                <i class="fas fa-user-plus"></i> Registrasi Pasien Baru (Walk-in)
            </a>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Urut</th>
                        <th>Waktu Registrasi</th>
                        <th>Status</th>
                        <th>Pasien (Pet)</th>
                        <th>Pemilik</th>
                        <th>Dokter</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendaftarans as $pendaftaran)
                        <tr>
                            <td>{{ $pendaftaran->no_urut }}</td>
                            <td>{{ \Carbon\Carbon::parse($pendaftaran->waktu_temu)->format('H:i') }} WIB</td>
                            <td>
                                @php
                                    $statusClass = '';
                                    if ($pendaftaran->status == 'Pending') $statusClass = 'status-pending';
                                    elseif ($pendaftaran->status == 'Dikonfirmasi') $statusClass = 'status-confirmed';
                                    elseif ($pendaftaran->status == 'Selesai') $statusClass = 'status-completed';
                                    else $statusClass = 'status-cancelled';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $pendaftaran->status }}</span>
                            </td>
                            <td>{{ $pendaftaran->pet->nama ?? 'N/A' }}</td>
                            <td>{{ $pendaftaran->pet->pemilik->nama_pemilik ?? 'N/A' }}</td>
                            <td>{{ $pendaftaran->roleUser->user->nama ?? 'N/A' }}</td>
                            <td class="action-buttons">
                                {{-- Link Edit mengarah ke Edit Pendaftaran --}}
                                <a href="{{ route('resepsionis.pendaftaran.edit', $pendaftaran->idreservasi_dokter) }}" class="edit-btn">
                                    <i class="fas fa-edit"></i> Kelola
                                </a>
                                {{-- Form Delete --}}
                                <form action="{{ route('resepsionis.pendaftaran.destroy', $pendaftaran->idreservasi_dokter) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
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
    .status-completed { background-color: #3498db; }
    .status-cancelled { background-color: #e74c3c; }
</style>
@endpush