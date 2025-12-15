@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1><i class="fas fa-plus"></i> Buat Janji Temu Baru (Pendaftaran)</h1>
        
        <a href="{{ route('resepsionis.temu-dokter.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Janji Temu
        </a>

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('resepsionis.temu-dokter.store') }}" method="POST">
            @csrf

            {{-- Pasien (Pet) --}}
            <div class="form-group">
                <label for="idpet">Pilih Pasien (Pet) <span class="text-danger">*</span></label>
                {{-- Menggunakan class select2-field untuk fitur pencarian --}}
                <select id="idpet" name="idpet" required class="form-control select2-field">
                    <option value="">-- Cari Nama Pet atau Pemilik --</option>
                    {{-- Variabel $pets dikirim dari TemuDokterController@create --}}
                    @foreach ($pets as $pet)
                        <option value="{{ $pet->idpet }}" {{ old('idpet') == $pet->idpet ? 'selected' : '' }}>
                            {{ $pet->nama }} (Pemilik: {{ $pet->pemilik->nama_pemilik ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('idpet')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Dokter (FK di form menggunakan ID User, bukan ID Role User) --}}
            <div class="form-group">
                <label for="iddokter">Pilih Dokter <span class="text-danger">*</span></label>
                <select id="iddokter" name="iddokter" required class="form-control select2-field">
                    <option value="">-- Pilih Dokter --</option>
                    {{-- Variabel $dokters berisi model User yang ber-role Dokter --}}
                    @foreach ($dokters as $dokter)
                        <option value="{{ $dokter->iduser }}" {{ old('iddokter') == $dokter->iduser ? 'selected' : '' }}>
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
                    value="{{ old('tanggal_temu', date('Y-m-d')) }}"
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
                    value="{{ old('waktu_temu', date('H:i')) }}"
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
                >{{ old('alasan') }}</textarea>
                @error('alasan')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- Status (Default Pending) --}}
            <input type="hidden" name="status" value="Pending">

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Janji Temu
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
            $('.select2-field').select2({
                theme: "default"
            });
        });
    </script>
@endpush