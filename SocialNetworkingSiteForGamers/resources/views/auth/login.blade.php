
@extends('main')

@section('title', 'Logowanie')

@push('head')
<link rel="stylesheet" href="{{ asset('home.css') }}">
<style>
    body {
        background: #f4f4f4;
    }
    .login-panel {
        max-width: 400px;
        margin: 60px auto 0 auto;
        background: var(--color-primary);
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.13);
        padding: 2.5rem 2rem 2rem 2rem;
        color: #fff;
    }
    .login-panel .logo {
        width: 70px;
        display: block;
        margin: 0 auto 1rem auto;
    }
    .login-panel h2 {
        color: #4f8cff;
        font-weight: bold;
        text-align: center;
        margin-bottom: 1.5rem;
        letter-spacing: 1px;
    }
    .login-panel label {
        color: #b0b8c1;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    .login-panel input {
        background: #f4f4f4;
        color: #23272f;
        border-radius: 6px;
        border: 1px solid #444;
        margin-bottom: 1rem;
    }
    .login-panel input:focus {
        border-color: #4f8cff;
        box-shadow: none;
    }
    .login-panel .btn-primary {
        background: #4f8cff;
        border: none;
        font-weight: bold;
        font-size: 1.1rem;
        width: 100%;
        margin-top: 0.5rem;
    }
    .login-panel .btn-primary:hover {
        background: #3973cc;
    }
    .login-panel .register-link {
        display: block;
        text-align: center;
        margin-top: 1.5rem;
        color: #b0b8c1;
    }
    .login-panel .register-link a {
        color: #4f8cff;
        text-decoration: underline;
    }
    .login-panel .alert {
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="login-panel">
        <img src="{{ asset('IMG/LogoArcadeUnionDefault.png') }}" alt="ArcadeUnion Logo" class="logo">
        <h2>Zaloguj się</h2>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Adres e-mail</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-2">
                <label for="password" class="form-label">Hasło</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required autocomplete="current-password">
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Zaloguj się</button>
        </form>

        <div class="register-link">
            Nie masz konta?
            <a href="{{ route('register') }}">Zarejestruj się</a>
        </div>
    </div>
</div>
@endsection