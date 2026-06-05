@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Produtos</h2>
            <p>Gerenciamento de produtos do catálogo.</p>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriar">
            <i class="bi bi-plus-lg"></i> Criar Produto
        </button>
    </div>

    <!-- Grid de Cards -->
    <div class="row g-4">
        @forelse($produtos as $produto)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="produto-card {{ $produto->status == 2 ? 'produto-inativo' : '' }}">
                    <!-- Imagem Principal -->
                    <div class="produto-card-img-wrapper btn-visualizar" data-id="{{ $produto->id }}" role="button">
                        @if($produto->imagemPrincipal)
                            <img src="{{ asset('storage/' . $produto->imagemPrincipal->path) }}" class="produto-card-img" alt="{{ $produto->produto }}">
                        @else
                            <div class="produto-card-img produto-card-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                        <div class="produto-card-overlay">
                            <i class="bi bi-eye"></i>
                        </div>
                        <span class="produto-card-estoque {{ $produto->qtd_estoque == 0 ? 'sem-estoque' : '' }}">
                            <i class="bi bi-box-seam"></i> {{ $produto->qtd_estoque }}
                        </span>
                    </div>

                    <!-- Thumbnails -->
                    @if($produto->imagens->count() > 1)
                        <div class="produto-card-thumbs">
                            @foreach($produto->imagens->take(4) as $img)
                                <div class="produto-thumb {{ $img->principal ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $img->path) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Info -->
                    <div class="produto-card-body">
                        <h6 class="produto-card-nome" title="{{ $produto->produto }}">{{ $produto->produto }}</h6>
                        <span class="produto-card-categoria">{{ $produto->categoria->categoria ?? '—' }}</span>
                        <div class="produto-card-preco">
                            R$ {{ number_format($produto->valor, 2, ',', '.') }}
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="produto-card-actions">
                        <button type="button" class="produto-action-btn btn-visualizar" data-id="{{ $produto->id }}" title="Visualizar">
                            <i class="bi bi-eye"></i> Ver
                        </button>
                        <button type="button" class="produto-action-btn btn-editar" data-id="{{ $produto->id }}" title="Editar">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                        <button type="button" class="produto-action-btn btn-toggle-status"
                            data-id="{{ $produto->id }}"
                            data-status="{{ $produto->status }}"
                            title="{{ $produto->status == 1 ? 'Inativar' : 'Ativar' }}">
                            <i class="bi bi-{{ $produto->status == 1 ? 'x-circle' : 'check-circle' }}"></i>
                            {{ $produto->status == 1 ? 'Inativar' : 'Ativar' }}
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                    <p class="text-muted mt-2 mb-0">Nenhum produto cadastrado.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Modal Criar -->
    <x-modal id="modalCriar" title="Criar Produto" icon="box">
        <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="produto" class="form-label fw-medium">Nome do Produto</label>
                        <input type="text" class="form-control" id="produto" name="produto" required placeholder="Nome do produto">
                    </div>
                    <div class="col-md-6">
                        <label for="id_categoria" class="form-label fw-medium">Categoria</label>
                        <select class="form-select" id="id_categoria" name="id_categoria" required>
                            <option value="">Selecione...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_filial" class="form-label fw-medium">Filial</label>
                        <select class="form-select" id="id_filial" name="id_filial" required>
                            <option value="">Selecione...</option>
                            @foreach($filiaisUsuario as $filial)
                                <option value="{{ $filial->id }}">{{ $filial->id_rm }} - {{ $filial->filial }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="valor" class="form-label fw-medium">Valor (R$)</label>
                        <input type="text" class="form-control mask-money" id="valor" name="valor" required placeholder="0,00">
                    </div>
                    <div class="col-md-6">
                        <label for="qtd_estoque" class="form-label fw-medium">Qtd. Estoque</label>
                        <input type="number" class="form-control" id="qtd_estoque" name="qtd_estoque" required min="0" value="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Imagens <small class="text-muted">(<span id="contadorImgCriar">0</span>/4 — clique na estrela para definir a principal)</small></label>
                        <div id="dropzoneCriar" class="dropzone-area">
                            <input type="file" id="imagens" name="imagens[]" multiple accept="image/jpeg,image/png,image/webp" class="dropzone-input">
                            <div class="dropzone-content">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <p>Arraste imagens aqui ou <span class="text-primary fw-medium">clique para selecionar</span></p>
                                <small class="text-muted">JPG, PNG ou WebP • Máx. 3MB cada</small>
                            </div>
                        </div>
                        <div id="previewCriar" class="img-preview-grid mt-2"></div>
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
    <x-modal id="modalVisualizar" title="Detalhes do Produto" icon="box">
        <div class="modal-body pt-3">
            <div class="row g-2">
                <div class="col-12">
                    <small class="text-muted d-block">Produto</small>
                    <span id="viewProduto" class="fw-medium">—</span>
                </div>
                <div class="col-6 mt-2">
                    <small class="text-muted d-block">Categoria</small>
                    <span id="viewCategoria" class="fw-medium">—</span>
                </div>
                <div class="col-6 mt-2">
                    <small class="text-muted d-block">Filial</small>
                    <span id="viewFilial" class="fw-medium">—</span>
                </div>
                <div class="col-6 mt-2">
                    <small class="text-muted d-block">Valor</small>
                    <span id="viewValor" class="fw-medium">—</span>
                </div>
                <div class="col-6 mt-2">
                    <small class="text-muted d-block">Estoque</small>
                    <span id="viewEstoque" class="fw-medium">—</span>
                </div>
                <div class="col-12 mt-3">
                    <small class="text-muted d-block mb-2">Imagens</small>
                    <div id="viewImagens" class="d-flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer border-top-0">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
        </div>
    </x-modal>

    <!-- Modal Editar -->
    <x-modal id="modalEditar" title="Editar Produto" icon="pencil">
        <form id="formEditar" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body pt-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="editProduto" class="form-label fw-medium">Nome do Produto</label>
                        <input type="text" class="form-control" id="editProduto" name="produto" required>
                    </div>
                    <div class="col-md-6">
                        <label for="editCategoria" class="form-label fw-medium">Categoria</label>
                        <select class="form-select" id="editCategoria" name="id_categoria" required>
                            <option value="">Selecione...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="editFilial" class="form-label fw-medium">Filial</label>
                        <select class="form-select" id="editFilial" name="id_filial" required>
                            <option value="">Selecione...</option>
                            @foreach($filiaisUsuario as $filial)
                                <option value="{{ $filial->id }}">{{ $filial->id_rm }} - {{ $filial->filial }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="editValor" class="form-label fw-medium">Valor (R$)</label>
                        <input type="text" class="form-control mask-money" id="editValor" name="valor" required>
                    </div>
                    <div class="col-md-6">
                        <label for="editEstoque" class="form-label fw-medium">Qtd. Estoque</label>
                        <input type="number" class="form-control" id="editEstoque" name="qtd_estoque" required min="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Imagens Atuais <small class="text-muted">(clique na estrela para definir a principal)</small></label>
                        <div id="editImagensAtuais" class="img-preview-grid mb-2"></div>
                        <input type="hidden" id="inputImagemPrincipal" name="imagem_principal" value="">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Adicionar Imagens <small class="text-muted">(<span id="contadorImgEditar">0</span>/4 no total)</small></label>
                        <div id="dropzoneEditar" class="dropzone-area">
                            <input type="file" id="editImagens" name="imagens[]" multiple accept="image/jpeg,image/png,image/webp" class="dropzone-input">
                            <div class="dropzone-content">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <p>Arraste imagens aqui ou <span class="text-primary fw-medium">clique para selecionar</span></p>
                                <small class="text-muted">JPG, PNG ou WebP • Máx. 3MB cada</small>
                            </div>
                        </div>
                        <div id="previewEditar" class="img-preview-grid mt-2"></div>
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
        message="Tem certeza que deseja alterar o status deste produto?"
        confirmText="Confirmar"
        confirmClass="btn-warning"
        icon="exclamation-triangle"
    />

    <!-- Modal Alerta -->
    <div class="modal fade" id="modalAlerta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center py-4 px-4">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: #fde8e8;">
                            <i class="bi bi-exclamation-circle text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h6 class="fw-semibold mb-2">Atenção</h6>
                    <p class="mb-0 text-muted small" id="modalAlertaMsg"></p>
                </div>
                <div class="modal-footer border-top-0 justify-content-center pb-4 pt-0">
                    <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Entendi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lightbox (visualização de imagem ampliada) -->
    <div class="modal fade" id="modalLightbox" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bg-transparent shadow-none">
                <div class="modal-body p-0 text-center position-relative">
                    <button type="button" class="lightbox-close-btn" data-bs-dismiss="modal" aria-label="Fechar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <img id="lightboxImg" src="" class="lightbox-img" alt="Imagem do produto">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Card Container */
    .produto-card {
        background: #fff;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .produto-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(0,0,0,0.12);
    }
    .produto-card.produto-inativo {
        opacity: 0.5;
        filter: grayscale(50%);
    }

    /* Imagem Principal */
    .produto-card-img-wrapper {
        position: relative;
        height: 200px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
    }
    .produto-card-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.25s ease;
    }
    .produto-card-overlay i {
        font-size: 2rem;
        color: #fff;
    }
    .produto-card-img-wrapper:hover .produto-card-overlay {
        opacity: 1;
    }
    .produto-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .produto-card:hover .produto-card-img {
        transform: scale(1.08);
    }
    .produto-card-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }
    .produto-card-placeholder i {
        font-size: 3.5rem;
        color: #ced4da;
    }
    .produto-card-estoque {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(255,255,255,0.95);
        color: #495057;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .produto-card-estoque.sem-estoque {
        background: #dc3545;
        color: #fff;
    }

    /* Thumbnails */
    .produto-card-thumbs {
        display: flex;
        gap: 6px;
        padding: 10px 14px;
        border-bottom: 1px solid #f0f2f5;
    }
    .produto-thumb {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        overflow: hidden;
        border: 2px solid #e9ecef;
        cursor: pointer;
        transition: border-color 0.15s;
    }
    .produto-thumb.active {
        border-color: #0d6efd;
    }
    .produto-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Body */
    .produto-card-body {
        padding: 1rem 1.1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .produto-card-nome {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.35rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .produto-card-categoria {
        font-size: 0.72rem;
        font-weight: 600;
        color: #198754;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.6rem;
    }
    .produto-card-preco {
        font-size: 1.1rem;
        font-weight: 800;
        color: #212529;
        margin-top: auto;
    }

    /* Ações */
    .produto-card-actions {
        display: flex;
        border-top: 1px solid #f0f2f5;
    }
    .produto-action-btn {
        flex: 1;
        border: none;
        background: transparent;
        padding: 0.65rem 0.25rem;
        font-size: 0.72rem;
        font-weight: 500;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }
    .produto-action-btn:not(:last-child) {
        border-right: 1px solid #f0f2f5;
    }
    .produto-action-btn:hover {
        background: #f8f9fa;
        color: #0d6efd;
    }
    .produto-action-btn.btn-toggle-status:hover {
        color: #e0a800;
    }

    /* Dropzone */
    .dropzone-area {
        position: relative;
        border: 2px dashed #dee2e6;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        background: #fafbfc;
    }
    .dropzone-area:hover,
    .dropzone-area.dragover {
        border-color: #0d6efd;
        background: #f0f6ff;
    }
    .dropzone-area .dropzone-input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }
    .dropzone-content i {
        font-size: 2rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    .dropzone-area:hover .dropzone-content i,
    .dropzone-area.dragover .dropzone-content i {
        color: #0d6efd;
    }
    .dropzone-content p {
        margin: 0.25rem 0 0;
        font-size: 0.875rem;
        color: #495057;
    }

    /* Preview Grid */
    .img-preview-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }
    .img-preview-item {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 2px solid #dee2e6;
        aspect-ratio: 1;
        transition: border-color 0.15s;
    }
    .img-preview-item.is-principal {
        border-color: #ffc107;
        box-shadow: 0 0 0 1px #ffc107;
    }
    .img-preview-item.removida {
        opacity: 0.3;
        border-color: #dc3545;
    }
    .img-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .img-preview-item .img-actions {
        position: absolute;
        top: 4px;
        right: 4px;
        display: flex;
        gap: 2px;
    }
    .img-preview-item .img-actions .btn {
        width: 22px;
        height: 22px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        border-radius: 50%;
        line-height: 1;
    }
    .img-preview-item .img-principal-star {
        position: absolute;
        top: 4px;
        left: 4px;
        font-size: 16px;
        cursor: pointer;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        transition: transform 0.15s;
    }
    .img-preview-item .img-principal-star:hover {
        transform: scale(1.2);
    }

    /* Lightbox */
    .lightbox-img {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 0.75rem;
        object-fit: contain;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }
    #modalLightbox .modal-dialog {
        max-width: 90vw;
    }
    .lightbox-close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: #fff;
        color: #333;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .lightbox-close-btn:hover {
        background: #dc3545;
        color: #fff;
        transform: scale(1.1);
    }
    .img-clickable {
        cursor: zoom-in;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/produtos/produtos.js') }}"></script>
@endpush
