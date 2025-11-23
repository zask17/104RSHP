@extends('layouts.app')

@section('title', 'Tambah Data Pasien (Pet)')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1>Tambah Data Pasien (Pet)</h1>
        
        <a href="{{ route('admin.pets.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pasien
        </a>

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.pets.store') }}" method="POST">
            @csrf

            {{-- Nama Pet --}}
            <div class="form-group">
                <label for="nama_pet">Nama Pasien <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="nama_pet"
                    name="nama_pet"
                    value="{{ old('nama_pet') }}"
                    placeholder="Masukkan nama hewan"
                    required
                >
                @error('nama_pet')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Pemilik --}}
            <div class="form-group">
                <label for="idpemilik">Pemilik <span class="text-danger">*</span></label>
                <select id="idpemilik" name="idpemilik" required>
                    <option value="">Pilih Pemilik</option>
                    @foreach ($pemilik as $item)
                        <option value="{{ $item->idpemilik }}" {{ old('idpemilik') == $item->idpemilik ? 'selected' : '' }}>
                            {{ $item->nama_pemilik }} ({{ $item->no_hp }})
                        </option>
                    @endforeach
                </select>
                @error('idpemilik')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jenis Hewan --}}
            <div class="form-group">
                <label for="idjenis_hewan">Jenis Hewan <span class="text-danger">*</span></label>
                <select id="idjenis_hewan" name="idjenis_hewan" required>
                    <option value="">Pilih Jenis Hewan</option>
                    @foreach ($jenisHewan as $item)
                        <option value="{{ $item->idjenis_hewan }}" {{ old('idjenis_hewan') == $item->idjenis_hewan ? 'selected' : '' }}>
                            {{ $item->nama_jenis_hewan }}
                        </option>
                    @endforeach
                </select>
                @error('idjenis_hewan')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Ras Hewan (Dropdown ini memerlukan JS untuk filter dinamis) --}}
            <div class="form-group">
                <label for="idras_hewan">Ras Hewan <span class="text-danger">*</span></label>
                <select id="idras_hewan" name="idras_hewan" required>
                    <option value="">Pilih Jenis Hewan terlebih dahulu</option>
                    {{-- Opsi Ras Hewan akan diisi di sini --}}
                    @foreach ($rasHewan as $ras)
                        <option 
                            value="{{ $ras->idras_hewan }}" 
                            data-idjenis="{{ $ras->idjenis_hewan }}"
                            class="ras-option"
                            style="display:none;"
                            {{ old('idras_hewan') == $ras->idras_hewan ? 'selected' : '' }}
                        >
                            {{ $ras->nama_ras }}
                        </option>
                    @endforeach
                </select>
                @error('idras_hewan')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- Tanggal Lahir --}}
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input
                    type="date"
                    id="tanggal_lahir"
                    name="tanggal_lahir"
                    value="{{ old('tanggal_lahir') }}"
                >
                @error('tanggal_lahir')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Warna --}}
            <div class="form-group">
                <label for="warna">Warna</label>
                <input
                    type="text"
                    id="warna"
                    name="warna"
                    value="{{ old('warna') }}"
                    placeholder="Contoh: Coklat Putih"
                >
                @error('warna')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Data Pasien
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenisHewanSelect = document.getElementById('idjenis_hewan');
    const rasHewanSelect = document.getElementById('idras_hewan');
    const rasOptions = rasHewanSelect.querySelectorAll('.ras-option');

    function filterRas() {
        const selectedJenisId = jenisHewanSelect.value;
        let foundRas = false;

        // Reset and hide all options
        rasOptions.forEach(option => {
            option.style.display = 'none';
            option.removeAttribute('selected');
        });

        // Show options matching the selected Jenis Hewan
        rasOptions.forEach(option => {
            if (option.getAttribute('data-idjenis') === selectedJenisId) {
                option.style.display = '';
                if (!foundRas) {
                    option.setAttribute('selected', 'selected'); // Pilih ras pertama yang cocok
                    foundRas = true;
                }
            }
        });
        
        // Reset the selected value if the current value is no longer visible
        if (rasHewanSelect.querySelector('option:checked') && rasHewanSelect.querySelector('option:checked').style.display === 'none') {
            rasHewanSelect.value = '';
        }
    }

    jenisHewanSelect.addEventListener('change', filterRas);

    // Initial run to handle old() values or default state
    filterRas();
});
</script>
@endsection