@extends('layouts.app')

@section('content')
    <div class="page-container">
        <div class="page-header">
            <h1><i class="fas fa-calendar-check"></i> Manajemen Janji Temu Dokter</h1>
            <p>Kelola daftar janji temu pasien dengan dokter.</p>
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

            {{-- Button Tambah --}}
            <a href="{{ route('resepsionis.temu-dokter.create') }}" class="add-btn">
                <i class="fas fa-plus"></i> Buat Janji Temu Baru
            </a>

            <table class="data-table">
                <thead>
                    <tr>
                        {{-- <th>ID</th> --}}
                        <th>No. Urut</th>
                        <th>Status</th>
                        <th>Pasien (Pet)</th>
                        <th>Pemilik</th>
                        <th>Dokter</th>
                        <th>Waktu Temu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Pastikan TemuDokterController.php menggunakan 'temuDokters' untuk variabel ini --}}
                    @forelse ($temuDokters as $temu)
                        <tr>
                            {{-- Menggunakan idreservasi_dokter sebagai primary key yang pasti ada --}}
                            {{-- <td>{{ $temu->idreservasi_dokter }}</td>  --}}
                            
                            <td>{{ $temu->no_urut ?? 'N/A' }}</td>
                            
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
                            
                            
                            {{-- FIX: Menggunakan operator Nullsafe (?->) dan kolom nama_pemilik --}}
                            {{-- Pet Name --}}
                            <td>{{ $temu->pet?->nama ?? 'Pasien Dihapus' }}</td> 
                            
                            {{-- Pemilik Name (Mengakses relasi berlapis) --}}
                            <td>{{ $temu->pet?->pemilik?->nama_pemilik ?? 'Pemilik Dihapus' }}</td>
                            
                            {{-- Dokter Name (Mengakses relasi roleUser -> user) --}}
                            <td>{{ $temu->roleUser?->user?->nama ?? 'Dokter Dihapus' }}</td> 
                            
                            <td>{{ \Carbon\Carbon::parse($temu->tanggal_temu . ' ' . $temu->waktu_temu)->format('d M Y H:i') }}</td>
                            
                            <td class="action-buttons">
                                {{-- Button Edit --}}
                                <a href="{{ route('resepsionis.temu-dokter.edit', $temu->idreservasi_dokter) }}" class="edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                {{-- Form Delete --}}
                                <form action="{{ route('resepsionis.temu-dokter.destroy', $temu->idreservasi_dokter) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan janji temu ini? Tindakan ini tidak dapat dibatalkan.')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center;">Tidak ada janji temu yang terdaftar.</td>
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