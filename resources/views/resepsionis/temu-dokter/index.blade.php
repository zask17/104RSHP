@extends('layouts.app')

@section('content')
    <div class="page-container">
        <div class="page-header">
            <h1><i class="fas fa-calendar-check"></i> Manajemen Janji Temu Dokter</h1>
            <p>Kelola daftar janji temu pasien untuk jadwal mendatang.</p>
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

            <a href="{{ route('resepsionis.temu-dokter.create') }}" class="add-btn">
                <i class="fas fa-plus"></i> Buat Janji Temu Baru
            </a>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Status</th>
                        <th>Pasien (Pet)</th>
                        <th>Pemilik</th>
                        <th>Dokter</th>
                        <th>Waktu Temu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($temuDokters as $temu)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @php
                                    $statusClass = '';
                                    if ($temu->status == 'Pending') $statusClass = 'status-pending';
                                    elseif ($temu->status == 'Dikonfirmasi') $statusClass = 'status-confirmed';
                                    elseif ($temu->status == 'Selesai') $statusClass = 'status-completed';
                                    else $statusClass = 'status-cancelled';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $temu->status }}</span>
                            </td>
                            
                            <td>{{ $temu->pet?->nama ?? 'N/A' }}</td> 
                            <td>{{ $temu->pet?->pemilik?->nama_pemilik ?? 'N/A' }}</td>
                            <td>{{ $temu->roleUser?->user?->nama ?? 'N/A' }}</td> 
                            
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($temu->tanggal_temu)->translatedFormat('d M Y') }}</strong><br>
                                <small>{{ \Carbon\Carbon::parse($temu->waktu_temu)->format('H:i') }} WIB</small>
                            </td>
                            
                            <td class="action-buttons">
                                <a href="{{ route('resepsionis.temu-dokter.edit', $temu->idreservasi_dokter) }}" class="edit-btn">
                                    <i class="fas fa-edit"></i> Kelola
                                </a>
                                {{-- Tombol Hapus Dihilangkan Sesuai Permintaan --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">Tidak ada jadwal janji temu mendatang.</td>
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
        min-width: 90px;
        text-align: center;
    }
    .status-pending { background-color: #f39c12; }
    .status-confirmed { background-color: #2ecc71; }
    .status-completed { background-color: #3498db; }
    .status-cancelled { background-color: #e74c3c; }
</style>
@endpush