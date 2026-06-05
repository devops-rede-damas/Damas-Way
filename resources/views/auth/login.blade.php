@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="login-page">
        <div class="login-card">
            <div class="brand-title">
                <img src="{{ asset('img/logo_damas_way.png') }}" alt="Damas Way">
                <p>Acesso ao sistema</p>
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

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Seu e-mail" required autofocus>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input id="senha" type="password" class="form-control" name="senha" placeholder="Sua senha" required>
                </div>

                <button type="submit" class="btn btn-primary btn-login">Entrar</button>
            </form>

            <div class="login-footer" style="text-align: center; margin-top: 1rem;">
                <a href="{{ route('password.request') }}" style="color: var(--fp-primary); font-size: 0.85rem; text-decoration: none;">
                    Esqueceu a senha?
                </a>
            </div>

            <div class="login-info">
                <h6>Sistema de Solicitação de Produtos</h6>
                <p>Rede Damas Educacional</p>
            </div>
        </div>
    </div>
@endsection
