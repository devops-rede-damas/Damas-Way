@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h2>Dashboard</h2>
        <p>Bem-vindo ao sistema de solicitação de produtos.</p>
    </div>

    <div class="row g-3">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-cart3 text-primary" style="font-size: 2rem;"></i>
                    <h5 class="mt-2 mb-0">Pedidos</h5>
                    <p class="text-muted small mb-0">Gerenciar pedidos</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-box text-success" style="font-size: 2rem;"></i>
                    <h5 class="mt-2 mb-0">Produtos</h5>
                    <p class="text-muted small mb-0">Catálogo de produtos</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                    <h5 class="mt-2 mb-0">Ocorrências</h5>
                    <p class="text-muted small mb-0">Registros abertos</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="bi bi-truck text-info" style="font-size: 2rem;"></i>
                    <h5 class="mt-2 mb-0">Entregas</h5>
                    <p class="text-muted small mb-0">Em andamento</p>
                </div>
            </div>
        </div>
    </div>
@endsection
