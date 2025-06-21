@extends('main')

@section('title', 'Rejestracja')

@push('head')
<link rel="stylesheet" href="{{ asset('home.css') }}">
<style>
    body {
        background: #f4f4f4;
    }
    .register-panel {
        max-width: 430px;
        margin: 60px auto 0 auto;
        background: var(--color-primary);
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.13);
        padding: 2.5rem 2rem 2rem 2rem;
        color: #fff;
    }
    .register-panel .logo {
        width: 70px;
        display: block;
        margin: 0 auto 1rem auto;
    }
    .register-panel h2 {
        color: #4f8cff;
        font-weight: bold;
        text-align: center;
        margin-bottom: 1.5rem;
        letter-spacing: 1px;
    }
    .register-panel label {
        color: #b0b8c1;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    .register-panel input {
        background: #f4f4f4;
        color: #23272f;
        border-radius: 6px;
        border: 1px solid #444;
        margin-bottom: 1rem;
    }
    .register-panel input:focus {
        border-color: #4f8cff;
        box-shadow: none;
    }
    .register-panel .btn-primary {
        background: #4f8cff;
        border: none;
        font-weight: bold;
        font-size: 1.1rem;
        width: 100%;
        margin-top: 0.5rem;
    }
    .register-panel .btn-primary:hover {
        background: #3973cc;
    }
    .register-panel .login-link {
        display: block;
        text-align: center;
        margin-top: 1.5rem;
        color: #b0b8c1;
    }
    .register-panel .login-link a {
        color: #4f8cff;
        text-decoration: underline;
    }
    .register-panel .alert {
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="register-panel">
        <img src="{{ asset('IMG/LogoArcadeUnionDefault.png') }}" alt="ArcadeUnion Logo" class="logo">
        <h2>Załóż konto</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="username" class="form-label">Nazwa użytkownika</label>
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                       name="username" value="{{ old('username') }}" required autofocus autocomplete="username">
                @error('username')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Adres e-mail</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Hasło</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required autocomplete="new-password">
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Powtórz hasło</label>
                <input id="password_confirmation" type="password" class="form-control"
                       name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Zarejestruj się</button>
        </form>

        <div class="login-link">
            Masz już konto?
            <a href="{{ route('login') }}">Zaloguj się</a>
        </div>
    </div>
</div>
@endsection