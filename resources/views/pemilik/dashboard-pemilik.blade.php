@extends('layouts.app')

@section('content')
<div class="page-container">
    {{-- Header Sesuai Style Admin --}}
    <div class="page-header">
        <h1>Dashboard Pemilik</h1>
        <p>Selamat Datang, <strong>{{ $dataPemilik->nama_pemilik }}</strong>. Berikut informasi kesehatan peliharaan Anda.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Grid Info Profil & Pet (Menggunakan .dashboard-grid) --}}
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Profil Saya</h3>
            <p style="text-align: left; margin-left: 20px;">
                <strong>Nama:</strong> {{ $dataPemilik->nama_pemilik}}<br>
                <strong>WhatsApp:</strong> {{ $dataPemilik->no_wa }}<br>
                <strong>Email:</strong> {{ $dataPemilik->email }}<br>
                <strong>Alamat:</strong> {{ $dataPemilik->alamat }}
            </p>
        </div>

        <div class="dashboard-card">
            <h3>Peliharaan Saya</h3>
            <div style="text-align: left; max-height: 150px; overflow-y: auto; padding: 10px;">
                @forelse($peliharaan as $p)
                    <div style="border-bottom: 1px solid #ddd; padding: 5px 0;">
                        <strong>{{ $p->nama }}</strong> ({{ $p->nama_jenis_hewan }})<br>
                        <small>{{ $p->nama_ras }} - {{ $p->jenis_kelamin == '1' ? 'Jantan' : 'Betina' }}</small>
                    </div>
                @empty
                    <p>Belum ada hewan terdaftar.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tabel Jadwal Temu --}}
    <div class="subjudul">Jadwal Temu Dokter</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Pet</th>
                <th>Dokter</th>
                <th>Tanggal & Waktu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwalTemu as $jt)
                <tr>
                    <td>{{ $jt->nama_pet }}</td>
                    <td>{{ $jt->nama_dokter }}</td>
                    <td>{{ \Carbon\Carbon::parse($jt->tanggal_temu)->format('d M Y') }} | {{ $jt->waktu_temu }}</td>
                    <td>
                        <span class="status-badge {{ strtolower($jt->status) == 'pending' ? 'status-pending' : 'status-confirmed' }}">
                            {{ $jt->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align: center;">Tidak ada jadwal temu aktif.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tabel Rekam Medis --}}
    <div class="subjudul" style="margin-top: 50px;">Riwayat Rekam Medis</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Tanggal Periksa</th>
                <th>Nama Pet</th>
                <th>Dokter</th>
                <th>Diagnosa</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekamMedis as $rm)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($rm->tanggal_temu)->format('d M Y') }}</td>
                    <td>{{ $rm->nama_pet }}</td>
                    <td>{{ $rm->nama_dokter }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($rm->diagnosa, 50) }}</td>
                    <td>
                        <a href="#" class="edit-link" onclick="alert('Fitur detail dalam pengembangan')">Lihat Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align: center;">Belum ada riwayat kesehatan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection