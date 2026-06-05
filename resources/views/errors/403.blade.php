@extends('layouts.app')

@section('title', 'Acesso Negado')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 text-center">
            <i class="bi bi-shield-lock text-danger" style="font-size: 5rem;"></i>
            <h2 class="mt-3">Acesso Negado</h2>
            <p class="text-muted">Você não tem permissão para acessar esta página.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left me-1"></i> Voltar ao Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
