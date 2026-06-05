@extends('layouts.app')

@section('title', 'Níveis')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Níveis</h2>
            <p>Gerenciamento de níveis de acesso do sistema.</p>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriar">
            <i class="bi bi-plus-lg"></i> Criar Nível
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelaNiveis" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nível</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($niveis as $nivel)
                        <tr>
                            <td>{{ $nivel->id }}</td>
                            <td>{{ $nivel->nivel }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox"
                                        data-id="{{ $nivel->id }}"
                                        {{ $nivel->status == 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-editar"
                                    data-id="{{ $nivel->id }}"
                                    data-nivel="{{ $nivel->nivel }}">
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
    <x-modal id="modalCriar" title="Criar Nível" icon="plus-circle">
        <form action="{{ route('niveis.store') }}" method="POST">
            @csrf
            <div class="modal-body pt-3">
                <div class="mb-3">
                    <label for="nivel" class="form-label fw-medium">Nome do Nível</label>
                    <input type="text" class="form-control" id="nivel" name="nivel" required placeholder="Ex: Administrador, Gestor, Operador...">
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Criar</button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Editar -->
    <x-modal id="modalEditar" title="Editar Nível" icon="pencil">
        <form id="formEditar" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body pt-3">
                <div class="mb-3">
                    <label for="editNivel" class="form-label fw-medium">Nome do Nível</label>
                    <input type="text" class="form-control" id="editNivel" name="nivel" required>
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
        message="Tem certeza que deseja alterar o status deste nível?"
        confirmText="Confirmar"
        confirmClass="btn-warning"
        icon="exclamation-triangle"
    />
@endsection

@push('scripts')
<script src="{{ asset('js/niveis/niveis.js') }}"></script>
@endpush
