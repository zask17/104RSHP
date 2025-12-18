@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1>Tambah Pasien Baru</h1>
        <form action="{{ route('resepsionis.pets.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Pasien*</label>
                <input type="text" name="nama" required class="form-control">
            </div>

            <div class="form-group">
                <label>Pemilik*</label>
                <select name="idpemilik" required class="form-control">
                    <option value="">-- Pilih Pemilik --</option>
                    @foreach($pemiliks as $p)
                        <option value="{{ $p->idpemilik }}">{{ $p->nama_pemilik }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Jenis Hewan*</label>
                <select id="idjenis_hewan" name="idjenis_hewan" required class="form-control">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach($jenisHewans as $j)
                        <option value="{{ $j->idjenis_hewan }}">{{ $j->nama_jenis_hewan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Ras Hewan (Opsional)</label>
                <select id="idras_hewan" name="idras_hewan" class="form-control">
                    <option value="">-- Pilih Jenis Terlebih Dahulu --</option>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Lahir*</label>
                <input type="date" name="tanggal_lahir" required class="form-control">
            </div>

            <div class="form-group">
                <label>Jenis Kelamin*</label>
                <select name="jenis_kelamin" required class="form-control">
                    <option value="Jantan">Jantan</option>
                    <option value="Betina">Betina</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Pasien</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#idjenis_hewan').on('change', function() {
        let id = $(this).val();
        $('#idras_hewan').empty().append('<option value="">-- Loading... --</option>');
        if(id) {
            $.get('/get-ras/' + id, function(data) {
                $('#idras_hewan').empty().append('<option value="">-- Pilih Ras --</option>');
                $.each(data, function(key, value) {
                    $('#idras_hewan').append('<option value="'+ value.idras_hewan +'">'+ value.nama_ras +'</option>');
                });
            });
        }
    });
</script>
@endsection