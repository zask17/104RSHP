@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1>Edit Pasien: {{ $pet->nama }}</h1>
        <form action="{{ route('resepsionis.pets.update', $pet->idpet) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Jenis Hewan*</label>
                <select id="idjenis_hewan" name="idjenis_hewan" required class="form-control">
                    @foreach($jenisHewans as $j)
                        <option value="{{ $j->idjenis_hewan }}" {{ $pet->idjenis_hewan == $j->idjenis_hewan ? 'selected' : '' }}>
                            {{ $j->nama_jenis_hewan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Ras Hewan</label>
                <select id="idras_hewan" name="idras_hewan" class="form-control">
                    </select>
            </div>

            <button type="submit" class="btn btn-success">Perbarui Pasien</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function loadRas(id, selectedId = null) {
        if(id) {
            $.get('/get-ras/' + id, function(data) {
                $('#idras_hewan').empty().append('<option value="">-- Pilih Ras --</option>');
                $.each(data, function(key, value) {
                    let selected = (selectedId == value.idras_hewan) ? 'selected' : '';
                    $('#idras_hewan').append('<option value="'+ value.idras_hewan +'" '+ selected +'>'+ value.nama_ras +'</option>');
                });
            });
        }
    }

    // Load saat pertama kali halaman dibuka
    loadRas($('#idjenis_hewan').val(), "{{ $pet->idras_hewan }}");

    // Load saat Jenis Hewan diubah manual
    $('#idjenis_hewan').on('change', function() {
        loadRas($(this).val());
    });
</script>
@endsection