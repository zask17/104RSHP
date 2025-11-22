@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <h2>Data Master</h2>
        <p>Kelola data master sistem seperti jenis hewan, ras, kategori, dan kode tindakan terapi.</p>
        <a href="{{ route('admin.datamaster') }}" class="btn-dashboard">Kelola Data Master</a>
    </div>
@endsection