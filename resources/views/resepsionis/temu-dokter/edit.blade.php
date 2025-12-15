@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1><i class="fas fa-edit"></i> Edit Janji Temu Pasien: {{ $temuDokter->pet->nama ?? 'N/A' }}</h1>
        
        <a href="{{ route('resepsionis.temu-dokter.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Janji Temu
        </a>

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Menggunakan $temuDokter->idreservasi_dokter sebagai primary key --}}
        <form action="{{ route('resepsionis.temu-dokter.update', $temuDokter->idreservasi_dokter) }}" method="POST">
            @csrf
            @method('PUT') {{-- Gunakan metode PUT untuk update --}}

            {{-- Informasi Pasien (TIDAK BOLEH DIUBAH) --}}
            <div class="form-group">
                <label>Pasien Terdaftar</label>
                <input type="text" value="{{ $temuDokter->pet->nama ?? 'N/A' }} (Pemilik: {{ $temuDokter->pet->pemilik->nama_pemilik ?? 'N/A' }})" disabled>
                <input type="hidden" name="idpet" value="{{ $temuDokter->idpet }}">
            </div>

            {{-- Dokter (FK di form menggunakan ID User) --}}
            <div class="form-group">
                <label for="iddokter">Pilih Dokter <span class="text-danger">*</span></label>
                <select id="iddokter" name="iddokter" required class="form-control select2-field">
                    <option value="">-- Pilih Dokter --</option>
                    {{-- $temuDokter->iddokter di-set di Controller agar mudah diakses --}}
                    @foreach ($dokters as $dokter)
                        <option value="{{ $dokter->iduser }}" {{ old('iddokter', $temuDokter->iddokter) == $dokter->iduser ? 'selected' : '' }}>
                            {{ $dokter->nama }}
                        </option>
                    @endforeach
                </select>
                @error('iddokter')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- Tanggal Temu --}}
            <div class="form-group">
                <label for="tanggal_temu">Tanggal Temu <span class="text-danger">*</span></label>
                <input
                    type="date"
                    id="tanggal_temu"
                    name="tanggal_temu"
                    value="{{ old('tanggal_temu', $temuDokter->tanggal_temu) }}"
                    required
                >
                @error('tanggal_temu')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Waktu Temu --}}
            <div class="form-group">
                <label for="waktu_temu">Waktu Temu <span class="text-danger">*</span></label>
                <input
                    type="time"
                    id="waktu_temu"
                    name="waktu_temu"
                    value="{{ old('waktu_temu', $temuDokter->waktu_temu) }}"
                    required
                >
                @error('waktu_temu')
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
                    placeholder="Contoh: Panas dan tidak nafsu makan sejak 2 hari yang lalu"
                >{{ old('alasan', $temuDokter->alasan) }}</textarea>
                @error('alasan')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select id="status" name="status" required>
                    @php $currentStatus = old('status', $temuDokter->status); @endphp
                    <option value="Pending" {{ $currentStatus == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Dikonfirmasi" {{ $currentStatus == 'Dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="Selesai" {{ $currentStatus == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Dibatalkan" {{ $currentStatus == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Perbarui Janji Temu
            </button>
        </form>
    </div>
</div>

@push('styles')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 40px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
        }
    </style>
@endpush

@push('scripts')
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Select2 hanya perlu diaktifkan untuk dropdown Dokter
            $('#iddokter').select2({
                theme: "default"
            });
        });
    </script>
@endpush