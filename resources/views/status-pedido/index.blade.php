@extends('layouts.app')

@section('title', 'Status de Pedido')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Status de Pedido</h2>
            <p>Gerenciamento dos status disponíveis para pedidos.</p>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriar">
            <i class="bi bi-plus-lg"></i> Criar Status
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelaStatusPedido" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Status</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statusPedidos as $statusPedido)
                        <tr>
                            <td>{{ $statusPedido->id }}</td>
                            <td>{{ $statusPedido->nome_status }}</td>
                            <td>{{ $statusPedido->descricao ?? '-' }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox"
                                        data-id="{{ $statusPedido->id }}"
                                        {{ $statusPedido->status == 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-editar"
                                    data-id="{{ $statusPedido->id }}"
                                    data-nome-status="{{ $statusPedido->nome_status }}"
                                    data-descricao="{{ $statusPedido->descricao }}">
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
    <x-modal id="modalCriar" title="Criar Status de Pedido" icon="clipboard-plus">
        <form action="{{ route('status-pedido.store') }}" method="POST">
            @csrf
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="nome_status" class="form-label fw-medium">Nome do Status</label>
                        <input type="text" class="form-control" id="nome_status" name="nome_status" required placeholder="Ex: Aguardando Aprovação, Em Separação, Enviado...">
                    </div>
                    <div class="col-12">
                        <label for="descricao" class="form-label fw-medium">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Breve descrição do status (opcional)">
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
    <x-modal id="modalEditar" title="Editar Status de Pedido" icon="pencil">
        <form id="formEditar" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="editNomeStatus" class="form-label fw-medium">Nome do Status</label>
                        <input type="text" class="form-control" id="editNomeStatus" name="nome_status" required>
                    </div>
                    <div class="col-12">
                        <label for="editDescricao" class="form-label fw-medium">Descrição</label>
                        <input type="text" class="form-control" id="editDescricao" name="descricao" placeholder="Breve descrição do status (opcional)">
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
        message="Tem certeza que deseja alterar o status deste registro?"
        confirmText="Confirmar"
        confirmClass="btn-warning"
        icon="exclamation-triangle"
    />
@endsection

@push('scripts')
<script src="{{ asset('js/status-pedido/status-pedido.js') }}"></script>
@endpush
