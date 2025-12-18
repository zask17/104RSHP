@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1>Edit User: {{ $user->nama }}</h1>
        
        <form action="{{ route('admin.users.update', $user->iduser) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label>Role/Jabatan</label>
                <select name="idrole" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->idrole }}" 
                            {{ (old('idrole', $currentRoleId) == $role->idrole) ? 'selected' : '' }}>
                            {{ $role->nama_role }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="info-box">
                <p>Kosongkan password jika tidak ingin mengganti.</p>
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password">
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation">
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection