@extends('layouts.app')

@section('title', 'Transportadoras')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Transportadoras</h2>
            <p>Gerenciamento de transportadoras.</p>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriar">
            <i class="bi bi-plus-lg"></i> Criar Transportadora
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelaTransportadoras" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>API</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transportadoras as $transportadora)
                        <tr>
                            <td>{{ $transportadora->id }}</td>
                            <td>{{ $transportadora->nome }}</td>
                            <td>
                                @if($transportadora->api)
                                    <span class="badge bg-light text-dark border">{{ $transportadora->api }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox"
                                        data-id="{{ $transportadora->id }}"
                                        {{ $transportadora->status == 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-editar"
                                    data-id="{{ $transportadora->id }}"
                                    data-nome="{{ $transportadora->nome }}"
                                    data-api="{{ $transportadora->api }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Criar -->
    <x-modal id="modalCriar" title="Criar Transportadora" icon="truck">
        <form action="{{ route('transportadoras.store') }}" method="POST">
            @csrf
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="nome" class="form-label fw-medium">Nome da Transportadora</label>
                        <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex: Correios, Jadlog, Azul Cargo...">
                    </div>
                    <div class="col-12">
                        <label for="api" class="form-label fw-medium">Endpoint API <small class="text-muted">(opcional)</small></label>
                        <input type="text" class="form-control" id="api" name="api" placeholder="URL da API de rastreio">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Criar</button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Editar -->
    <x-modal id="modalEditar" title="Editar Transportadora" icon="pencil">
        <form id="formEditar" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="editNome" class="form-label fw-medium">Nome da Transportadora</label>
                        <input type="text" class="form-control" id="editNome" name="nome" required>
                    </div>
                    <div class="col-12">
                        <label for="editApi" class="form-label fw-medium">Endpoint API <small class="text-muted">(opcional)</small></label>
                        <input type="text" class="form-control" id="editApi" name="api" placeholder="URL da API de rastreio">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Confirmação Toggle Status -->
    <x-modal-confirmacao
        id="modalToggleStatus"
        title="Alterar Status"
        message="Tem certeza que deseja alterar o status desta transportadora?"
        confirmText="Confirmar"
        confirmClass="btn-warning"
        icon="exclamation-triangle"
    />
@endsection

@push('scripts')
<script src="{{ asset('js/transportadoras/transportadoras.js') }}"></script>
@endpush
