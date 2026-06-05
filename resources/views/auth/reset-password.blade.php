@extends('layouts.auth')

@section('title', 'Redefinir Senha')

@section('content')
    <div class="login-page">
        <div class="login-card">
            <div class="brand-title">
                <img src="{{ asset('img/logo_damas_way.png') }}" alt="Damas Way">
                <p>Nova senha</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="senha">Nova senha</label>
                    <input id="senha" type="password" class="form-control" name="senha" placeholder="Mínimo 6 caracteres" required>
                </div>

                <div class="form-group">
                    <label for="senha_confirmation">Confirmar senha</label>
                    <input id="senha_confirmation" type="password" class="form-control" name="senha_confirmation" placeholder="Repita a nova senha" required>
                </div>

                <button type="submit" class="btn btn-primary btn-login">Redefinir senha</button>
            </form>

            <div class="login-info">
                <a href="{{ route('login') }}" style="color: var(--fp-primary); text-decoration: none; font-size: 0.85rem;">
                    <i class="bi bi-arrow-left"></i> Voltar ao login
                </a>
            </div>
        </div>
    </div>
@endsection
