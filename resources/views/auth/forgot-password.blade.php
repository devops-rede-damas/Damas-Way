@extends('layouts.auth')

@section('title', 'Esqueceu a Senha')

@section('content')
    <div class="login-page">
        <div class="login-card">
            <div class="brand-title">
                <img src="{{ asset('img/logo_damas_way.png') }}" alt="Damas Way">
                <p>Recuperar senha</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Informe seu e-mail cadastrado" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary btn-login">Enviar link de redefinição</button>
            </form>

            <div class="login-info">
                <a href="{{ route('login') }}" style="color: var(--fp-primary); text-decoration: none; font-size: 0.85rem;">
                    <i class="bi bi-arrow-left"></i> Voltar ao login
                </a>
            </div>
        </div>
    </div>
@endsection
