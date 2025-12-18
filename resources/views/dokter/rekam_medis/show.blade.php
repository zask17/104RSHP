@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Detail Rekam Medis: {{ $rekamMedis->nama_pet }}</h1>
        <p>Kelola tindakan klinis, terapi, dan catatan medis pasien.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Ringkasan Diagnosa --}}
    <div class="dashboard-card" style="text-align: left; margin-bottom: 30px;">
        <h3><i class="fas fa-stethoscope"></i> Diagnosa Utama</h3>
        <div style="padding: 15px;">
            <p><strong>Anamnesa:</strong> {{ $rekamMedis->anamnesa }}</p>
            <p><strong>Temuan Klinis:</strong> {{ $rekamMedis->temuan_klinis }}</p>
            <p><strong>Diagnosa:</strong> <span style="color: #e74c3c; font-weight: bold;">{{ $rekamMedis->diagnosa }}</span></p>
            <p><small>Dokter Pemeriksa: {{ $rekamMedis->nama_dokter }}</small></p>
        </div>
    </div>

    {{-- Daftar Tindakan (Read & Delete) --}}
    <div class="subjudul">Daftar Tindakan & Terapi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Kategori</th>
                <th>Tindakan / Terapi</th>
                <th>Catatan Detail</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($details as $d)
            <tr>
                <td><span class="status-badge status-completed">{{ $d->kode }}</span></td>
                <td>{{ $d->nama_kategori }}</td>
                <td>{{ $d->deskripsi_tindakan_terapi }}</td>
                <td>{{ $d->detail ?? '-' }}</td>
                <td style="text-align: center;">
                    {{-- Form Hapus --}}
                    <form action="{{ route('dokter.detail-rekam-medis.destroy', $d->iddetail_rekam_medis) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-link" onclick="return confirm('Apakah Anda yakin ingin menghapus tindakan ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Belum ada detail tindakan medis.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Form Tambah Tindakan (Create) --}}
    <div class="form-container" style="margin-top: 50px; background-color: #f9fbfd;">
        <h3 class="form-title-text"><i class="fas fa-plus-circle"></i> Tambah Tindakan Baru</h3>
        <form action="{{ route('dokter.detail-rekam-medis.store', $rekamMedis->idrekam_medis) }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Pilih Kode Tindakan/Terapi</label>
                <select name="idkode_tindakan_terapi" required>
                    <option value="">-- Pilih Tindakan --</option>
                    @foreach($kodeTindakan as $k)
                        <option value="{{ $k->idkode_tindakan_terapi }}">
                            [{{ $k->kode }}] {{ $k->deskripsi_tindakan_terapi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Catatan Detail (Dosis, Frekuensi, atau Instruksi)</label>
                <textarea name="detail" rows="3" placeholder="Contoh: Dosis 2x1 hari setelah makan..."></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Detail Tindakan
            </button>
        </form>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('dokter.rekam-medis.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Rekam Medis
        </a>
    </div>
</div>
@endsection