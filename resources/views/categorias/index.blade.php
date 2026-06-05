@extends('layouts.app')

@section('title', 'Categorias')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Categorias</h2>
            <p>Gerenciamento de categorias de produtos.</p>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriar">
            <i class="bi bi-plus-lg"></i> Criar Categoria
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelaCategorias" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>
                            <td>{{ $categoria->categoria }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox"
                                        data-id="{{ $categoria->id }}"
                                        {{ $categoria->status == 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-editar"
                                    data-id="{{ $categoria->id }}"
                                    data-categoria="{{ $categoria->categoria }}">
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
    <x-modal id="modalCriar" title="Criar Categoria" icon="tag">
        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="categoria" class="form-label fw-medium">Nome da Categoria</label>
                        <input type="text" class="form-control" id="categoria" name="categoria" required placeholder="Ex: Eletrônicos, Vestuário, Alimentos...">
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
    <x-modal id="modalEditar" title="Editar Categoria" icon="pencil">
        <form id="formEditar" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="editCategoria" class="form-label fw-medium">Nome da Categoria</label>
                        <input type="text" class="form-control" id="editCategoria" name="categoria" required>
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
        message="Tem certeza que deseja alterar o status desta categoria?"
        confirmText="Confirmar"
        confirmClass="btn-warning"
        icon="exclamation-triangle"
    />
@endsection

@push('scripts')
<script src="{{ asset('js/categorias/categorias.js') }}"></script>
@endpush
