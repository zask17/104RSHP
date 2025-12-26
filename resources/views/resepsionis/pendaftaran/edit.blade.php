@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1><i class="fas fa-edit"></i> Kelola Pendaftaran: {{ $pendaftaran->pet->nama ?? 'N/A' }}</h1>
        <p>No. Urut: {{ $pendaftaran->no_urut }} | Waktu Registrasi: {{ \Carbon\Carbon::parse($pendaftaran->waktu_temu)->format('H:i') }} WIB</p>
        
        <a href="{{ route('resepsionis.pendaftaran.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Antrean Pendaftaran
        </a>

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('resepsionis.pendaftaran.update', $pendaftaran->idreservasi_dokter) }}" method="POST">
            @csrf
            @method('PUT') {{-- Gunakan metode PUT untuk update --}}

            {{-- Pasien (Hidden, tidak bisa diubah) --}}
            <div class="form-group">
                <label>Pasien Terdaftar</label>
                <input type="text" value="{{ $pendaftaran->pet->nama ?? 'N/A' }} (Pemilik: {{ $pendaftaran->pet->pemilik->nama_pemilik ?? 'N/A' }})" disabled>
                <input type="hidden" name="idpet" value="{{ $pendaftaran->idpet }}">
            </div>

            {{-- Dokter --}}
            <div class="form-group">
                <label for="iddokter">Pilih Dokter Bertugas <span class="text-danger">*</span></label>
                <select id="iddokter" name="iddokter" required class="form-control select2-field">
                    <option value="">-- Pilih Dokter --</option>
                    @foreach ($dokters as $dokter)
                        <option value="{{ $dokter->iduser }}" {{ old('iddokter', $pendaftaran->iddokter) == $dokter->iduser ? 'selected' : '' }}>
                            {{ $dokter->nama }}
                        </option>
                    @endforeach
                </select>
                @error('iddokter')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- Alasan --}}
            <div class="form-group">
                <label for="alasan">Alasan/Keluhan Singkat</label>
                <textarea
                    id="alasan"
                    name="alasan"
                    rows="3"
                    placeholder="Contoh: Panas dan tidak nafsu makan"
                >{{ old('alasan', $pendaftaran->alasan) }}</textarea>
                @error('alasan')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label for="status">Status Antrean <span class="text-danger">*</span></label>
                <select id="status" name="status" required>
                    @php $currentStatus = old('status', $pendaftaran->status); @endphp
                    <option value="Pending" {{ $currentStatus == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Dikonfirmasi" {{ $currentStatus == 'Dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi (Siap diperiksa)</option>
                    <option value="Selesai" {{ $currentStatus == 'Selesai' ? 'selected' : '' }}>Selesai (Sudah diperiksa/selesai)</option>
                    <option value="Dibatalkan" {{ $currentStatus == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Perbarui Pendaftaran
            </button>
        </form>
    </div>
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single { height: 40px !important; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 38px !important; }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#iddokter').select2({ theme: "default" });
        });
    </script>
@endpush