@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
    <div class="page-header">
        <h2>Meu Perfil</h2>
        <p>Visualize e edite suas informações pessoais.</p>
    </div>

    <div class="row g-4">
        <!-- Informações do Perfil -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-1">{{ $usuario->nome }}</h5>
                    <p class="text-muted small mb-2">{{ $usuario->email }}</p>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $usuario->nivel->nivel ?? '—' }}</span>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <div class="row text-center g-0">
                        <div class="col">
                            <small class="text-muted d-block">Chapa</small>
                            <span class="fw-medium small">{{ $usuario->chapa }}</span>
                        </div>
                        <div class="col border-start">
                            <small class="text-muted d-block">Qtd. Filiais</small>
                            <span class="fw-medium small">{{ $filiaisVinculadas->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filiais Vinculadas -->
            @if($filiaisVinculadas->count())
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-transparent border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-geo-alt me-1"></i> Filiais Vinculadas</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($filiaisVinculadas as $filial)
                                <li class="list-group-item py-2 px-3">
                                    <span class="small">{{ $filial->id_rm }} - {{ $filial->filial }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <!-- Formulário de Edição -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-1"></i> Editar Informações</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('perfil.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Nome Completo</label>
                                <input type="text" class="form-control" value="{{ $usuario->nome }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">E-mail</label>
                                <input type="email" class="form-control" value="{{ $usuario->email }}" disabled>
                            </div>
                            <div class="col-12">
                                <hr class="my-2">
                                <p class="text-muted small mb-2"><i class="bi bi-shield-lock me-1"></i> Alterar senha (deixe em branco para manter a atual)</p>
                            </div>
                            <div class="col-md-6">
                                <label for="senha" class="form-label fw-medium">Nova Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Mínimo 6 caracteres" autocomplete="new-password">
                            </div>
                            <div class="col-md-6">
                                <label for="senha_confirmation" class="form-label fw-medium">Confirmar Senha</label>
                                <input type="password" class="form-control" id="senha_confirmation" name="senha_confirmation" placeholder="Repita a nova senha" autocomplete="new-password">
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i> Salvar Alterações
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
