@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Usuários</h2>
            <p>Gerenciamento de usuários do sistema.</p>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriar">
            <i class="bi bi-plus-lg"></i> Criar Usuário
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelaUsuarios" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Chapa</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Nível</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->chapa }}</td>
                            <td>{{ $usuario->nome }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $usuario->nivel->nivel ?? '—' }}</span></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox"
                                        data-id="{{ $usuario->id }}"
                                        {{ $usuario->status == 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-info btn-visualizar" data-id="{{ $usuario->id }}" title="Visualizar">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-editar" data-id="{{ $usuario->id }}" title="Editar">
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
    <x-modal id="modalCriar" title="Criar Usuário" icon="person-plus">
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="chapa" class="form-label fw-medium">Chapa</label>
                        <input type="text" class="form-control" id="chapa" name="chapa" required placeholder="Chapa do funcionário">
                    </div>
                    <div class="col-md-6">
                        <label for="nivel_id" class="form-label fw-medium">Nível de Acesso</label>
                        <select class="form-select" id="nivel_id" name="nivel_id" required>
                            <option value="">Selecione...</option>
                            @foreach($niveis as $nivel)
                                <option value="{{ $nivel->id }}">{{ $nivel->nivel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="nome" class="form-label fw-medium">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" required placeholder="Nome do usuário">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-medium">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="email@exemplo.com">
                    </div>
                    <div class="col-md-6">
                        <label for="senha" class="form-label fw-medium">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required placeholder="Mínimo 6 caracteres">
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-medium mb-0">Filiais Vinculadas</label>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="checkbox" id="criarSelectAll">
                                <label class="form-check-label small text-muted" for="criarSelectAll">Selecionar todas</label>
                            </div>
                        </div>
                        <div class="filiais-container border rounded-3 p-3" style="max-height: 220px; overflow-y: auto;">
                            @foreach($coligadas as $coligada)
                                @if($filiais->where('id_coligada', $coligada->id)->count())
                                <div class="filiais-grupo {{ !$loop->first ? 'mt-3 pt-3 border-top' : '' }}">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-building me-2 text-primary" style="font-size: 0.8rem;"></i>
                                        <span class="fw-semibold small text-primary">{{ $coligada->coligada }}</span>
                                    </div>
                                    <div class="row g-1 ps-4">
                                        @foreach($filiais->where('id_coligada', $coligada->id) as $filial)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input criar-filial-check" type="checkbox" name="filiais[]" value="{{ $filial->id }}" id="filial_criar_{{ $filial->id }}">
                                                    <label class="form-check-label" for="filial_criar_{{ $filial->id }}">{{ $filial->id_rm }} - {{ $filial->filial }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Criar</button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Visualizar -->
    <x-modal id="modalVisualizar" title="Detalhes do Usuário" icon="person">
        <div class="modal-body pt-3">
            <div class="row g-2">
                <div class="col-6">
                    <small class="text-muted d-block">Chapa</small>
                    <span id="viewChapa" class="fw-medium">—</span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Nível</small>
                    <span id="viewNivel" class="fw-medium">—</span>
                </div>
                <div class="col-12 mt-2">
                    <small class="text-muted d-block">Nome</small>
                    <span id="viewNome" class="fw-medium">—</span>
                </div>
                <div class="col-12 mt-2">
                    <small class="text-muted d-block">E-mail</small>
                    <span id="viewEmail" class="fw-medium">—</span>
                </div>
                <div class="col-12 mt-3">
                    <small class="text-muted d-block mb-1">Filiais Vinculadas</small>
                    <div id="viewFiliais">—</div>
                </div>
            </div>
        </div>
        <div class="modal-footer border-top-0">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
        </div>
    </x-modal>

    <!-- Modal Editar -->
    <x-modal id="modalEditar" title="Editar Usuário" icon="pencil">
        <form id="formEditar" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="editChapa" class="form-label fw-medium">Chapa</label>
                        <input type="text" class="form-control" id="editChapa" name="chapa" required>
                    </div>
                    <div class="col-md-6">
                        <label for="editNivelId" class="form-label fw-medium">Nível de Acesso</label>
                        <select class="form-select" id="editNivelId" name="nivel_id" required>
                            <option value="">Selecione...</option>
                            @foreach($niveis as $nivel)
                                <option value="{{ $nivel->id }}">{{ $nivel->nivel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="editNome" class="form-label fw-medium">Nome Completo</label>
                        <input type="text" class="form-control" id="editNome" name="nome" required>
                    </div>
                    <div class="col-md-6">
                        <label for="editEmail" class="form-label fw-medium">E-mail</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="editSenha" class="form-label fw-medium">Senha</label>
                        <input type="password" class="form-control" id="editSenha" name="senha" placeholder="Deixe vazio para manter">
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-medium mb-0">Filiais Vinculadas</label>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="checkbox" id="editarSelectAll">
                                <label class="form-check-label small text-muted" for="editarSelectAll">Selecionar todas</label>
                            </div>
                        </div>
                        <div class="filiais-container border rounded-3 p-3" id="editFiliaisContainer" style="max-height: 220px; overflow-y: auto;">
                            @foreach($coligadas as $coligada)
                                @if($filiais->where('id_coligada', $coligada->id)->count())
                                <div class="filiais-grupo {{ !$loop->first ? 'mt-3 pt-3 border-top' : '' }}">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-building me-2 text-primary" style="font-size: 0.8rem;"></i>
                                        <span class="fw-semibold small text-primary">{{ $coligada->coligada }}</span>
                                    </div>
                                    <div class="row g-1 ps-4">
                                        @foreach($filiais->where('id_coligada', $coligada->id) as $filial)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input edit-filial-check" type="checkbox" name="filiais[]" value="{{ $filial->id }}" id="filial_editar_{{ $filial->id }}">
                                                    <label class="form-check-label" for="filial_editar_{{ $filial->id }}">{{ $filial->id_rm }} - {{ $filial->filial }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
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
        message="Tem certeza que deseja alterar o status deste usuário?"
        confirmText="Confirmar"
        confirmClass="btn-warning"
        icon="exclamation-triangle"
    />
@endsection

@push('scripts')
<script src="{{ asset('js/usuarios/usuarios.js') }}"></script>
@endpush
