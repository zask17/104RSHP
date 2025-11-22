@extends('layouts.app') 

@section('title', 'Data Master - RSHP UNAIR')

@section('content')

    <div class="page-container">
        <div class="page-header">
            <h1>Data Master</h1>
            <p>Pilih salah satu menu di bawah untuk mengelola data sistem.</p>
        </div>

        {{-- <div class="nav-grid">
            <a href="{{ route('admin.datamaster.user') }}" class="nav-card">
                <h3>Data User</h3>
                <p>Kelola data, peran, dan kata sandi pengguna sistem.</p>
            </a> --}}
            
            {{-- Tambahkan tautan-tautan sub-menu lainnya menggunakan route('admin.datamaster.nama_rute') --}}
        </div>
    </div>
@endsection