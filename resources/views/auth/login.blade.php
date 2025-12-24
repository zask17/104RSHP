@extends('layouts.pages')

@section('content')
<div class="login-container">
    <div class="login-form">
        <h2 class="subjudul">{{ __('Login') }}</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            @if ($errors->any())
                <div class="alert alert-basic">
                    {{ __('Email atau password salah.') }}
                </div>
            @endif

            <div class="form-group">
                <label for="email">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" style="color: red; font-size: 12px;"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" style="color: red; font-size: 12px;"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-check" style="margin-bottom: 15px;">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember" style="display: inline; font-weight: normal;">
                    {{ __('Remember Me') }}
                </label>
            </div>

            <button type="submit" class="btn-submit">
                {{ __('Login') }}
            </button>

            @if (Route::has('password.request'))
                <div style="margin-top: 15px;">
                    <a class="back-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection