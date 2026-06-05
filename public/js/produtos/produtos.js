$(document).ready(function() {

    // Modal de alerta
    function mostrarAlerta(mensagem) {
        $('#modalAlertaMsg').text(mensagem);
        $('#modalAlerta').modal('show');
    }

    // Máscara de moeda
    function maskMoney(input) {
        $(input).on('input', function() {
            var value = $(this).val().replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(value === '0,00' ? '' : value);
        });
    }

    maskMoney('.mask-money');

    // ========== DROPZONE - CRIAR ==========
    var arquivosCriar = [];
    var principalCriarIndex = 0;

    // Drag & drop visual
    $('#dropzoneCriar').on('dragover dragenter', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    }).on('dragleave drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    // Seleção de arquivos (input change ou drop)
    $('#imagens').on('change', function() {
        var novosArquivos = Array.from(this.files);
        adicionarArquivosCriar(novosArquivos);
    });

    function adicionarArquivosCriar(novos) {
        var total = arquivosCriar.length + novos.length;
        if (total > 4) {
            mostrarAlerta('São permitidas no máximo 4 imagens. Você já tem ' + arquivosCriar.length + '.');
            return;
        }

        novos.forEach(function(file) {
            if (file.size > 3145728) {
                mostrarAlerta('A imagem "' + file.name + '" excede 3MB.');
                return;
            }
            arquivosCriar.push(file);
        });

        renderPreviewCriar();
    }

    function renderPreviewCriar() {
        var preview = $('#previewCriar');
        preview.empty();
        $('#contadorImgCriar').text(arquivosCriar.length);

        if (principalCriarIndex >= arquivosCriar.length) {
            principalCriarIndex = 0;
        }

        arquivosCriar.forEach(function(file, index) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var isPrincipal = index === principalCriarIndex;
                var starClass = isPrincipal ? 'bi-star-fill text-warning' : 'bi-star text-white';
                var itemClass = isPrincipal ? 'img-preview-item is-principal' : 'img-preview-item';

                var html =
                    '<div class="' + itemClass + '" data-index="' + index + '">' +
                    '<img src="' + e.target.result + '">' +
                    '<span class="img-principal-star criar-set-principal" data-index="' + index + '"><i class="bi ' + starClass + '"></i></span>' +
                    '<div class="img-actions">' +
                    '<button type="button" class="btn btn-danger criar-remover-img" data-index="' + index + '"><i class="bi bi-x"></i></button>' +
                    '</div>' +
                    '</div>';

                // Inserir na posição correta
                var items = preview.find('.img-preview-item');
                if (index >= items.length) {
                    preview.append(html);
                } else {
                    $(items[index]).replaceWith(html);
                }
            };
            reader.readAsDataURL(file);
        });

        atualizarInputArquivosCriar();
    }

    function atualizarInputArquivosCriar() {
        // Recria o input file com os arquivos corretos via DataTransfer
        var dt = new DataTransfer();

        // Reordenar: principal primeiro
        var ordenados = [];
        if (arquivosCriar.length > 0) {
            ordenados.push(arquivosCriar[principalCriarIndex]);
            arquivosCriar.forEach(function(f, i) {
                if (i !== principalCriarIndex) ordenados.push(f);
            });
        }

        ordenados.forEach(function(file) {
            dt.items.add(file);
        });

        $('#imagens')[0].files = dt.files;
    }

    // Remover imagem no criar
    $(document).on('click', '.criar-remover-img', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var index = $(this).data('index');
        arquivosCriar.splice(index, 1);
        if (principalCriarIndex >= arquivosCriar.length) {
            principalCriarIndex = 0;
        }
        renderPreviewCriar();
    });

    // Definir principal no criar
    $(document).on('click', '.criar-set-principal', function(e) {
        e.preventDefault();
        e.stopPropagation();
        principalCriarIndex = $(this).data('index');
        renderPreviewCriar();
    });

    // ========== DROPZONE - EDITAR ==========
    $('#dropzoneEditar').on('dragover dragenter', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    }).on('dragleave drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    var arquivosEditar = [];

    $('#editImagens').on('change', function() {
        var novos = Array.from(this.files);
        adicionarArquivosEditar(novos);
    });

    function adicionarArquivosEditar(novos) {
        var imagensAtuais = $('#editImagensAtuais .img-preview-item:not(.removida)').length;
        var total = imagensAtuais + arquivosEditar.length + novos.length;

        if (total > 4) {
            mostrarAlerta('O produto pode ter no máximo 4 imagens. Slots disponíveis: ' + (4 - imagensAtuais - arquivosEditar.length));
            return;
        }

        novos.forEach(function(file) {
            if (file.size > 3145728) {
                mostrarAlerta('A imagem "' + file.name + '" excede 3MB.');
                return;
            }
            arquivosEditar.push(file);
        });

        renderPreviewEditar();
        atualizarContadorEditar();
    }

    function renderPreviewEditar() {
        var preview = $('#previewEditar');
        preview.empty();

        arquivosEditar.forEach(function(file, index) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.append(
                    '<div class="img-preview-item" data-index="' + index + '">' +
                    '<img src="' + e.target.result + '">' +
                    '<div class="img-actions">' +
                    '<button type="button" class="btn btn-danger editar-remover-nova" data-index="' + index + '"><i class="bi bi-x"></i></button>' +
                    '</div>' +
                    '<span class="position-absolute bottom-0 start-0 badge bg-success" style="font-size:9px;border-radius:0 0 0 0.4rem;">Nova</span>' +
                    '</div>'
                );
            };
            reader.readAsDataURL(file);
        });

        atualizarInputArquivosEditar();
    }

    function atualizarInputArquivosEditar() {
        var dt = new DataTransfer();
        arquivosEditar.forEach(function(file) {
            dt.items.add(file);
        });
        $('#editImagens')[0].files = dt.files;
    }

    function atualizarContadorEditar() {
        var imagensAtuais = $('#editImagensAtuais .img-preview-item:not(.removida)').length;
        var total = imagensAtuais + arquivosEditar.length;
        $('#contadorImgEditar').text(total);
    }

    // Remover nova imagem no editar
    $(document).on('click', '.editar-remover-nova', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var index = $(this).data('index');
        arquivosEditar.splice(index, 1);
        renderPreviewEditar();
        atualizarContadorEditar();
    });

    // ========== TOGGLE STATUS ==========
    var toggleAtualId = null;

    $(document).on('click', '.btn-toggle-status', function(e) {
        e.preventDefault();
        toggleAtualId = $(this).data('id');
        var status = $(this).data('status');
        var acao = status == 1 ? 'inativar' : 'ativar';
        $('#modalToggleStatusMsg').text('Tem certeza que deseja ' + acao + ' este produto?');
        $('#modalToggleStatus').modal('show');
    });

    $('#modalToggleStatusBtn').on('click', function() {
        if (!toggleAtualId) return;

        $.ajax({
            url: '/produtos/' + toggleAtualId + '/toggle-status',
            type: 'PATCH',
            success: function(response) {
                $('#modalToggleStatus').modal('hide');
                location.reload();
            },
            error: function() {
                $('#modalToggleStatus').modal('hide');
                mostrarAlerta('Erro ao alterar status.');
            }
        });
    });

    // ========== VISUALIZAR ==========
    $(document).on('click', '.btn-visualizar', function() {
        var id = $(this).data('id');

        $.get('/produtos/' + id, function(data) {
            $('#viewProduto').text(data.produto.produto);
            $('#viewCategoria').text(data.produto.categoria ? data.produto.categoria.categoria : '—');
            $('#viewFilial').text(data.filial_label);
            $('#viewValor').text('R$ ' + parseFloat(data.produto.valor).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            $('#viewEstoque').text(data.produto.qtd_estoque);

            var imgHtml = '';
            if (data.imagens && data.imagens.length > 0) {
                imgHtml = '<div class="img-preview-grid">';
                data.imagens.forEach(function(img) {
                    var principalClass = img.principal == 1 ? ' is-principal' : '';
                    var badge = img.principal == 1 ? '<span class="img-principal-star"><i class="bi bi-star-fill text-warning"></i></span>' : '';
                    imgHtml += '<div class="img-preview-item' + principalClass + '">' + badge + '<img src="' + img.url + '" class="img-clickable"></div>';
                });
                imgHtml += '</div>';
            } else {
                imgHtml = '<span class="text-muted small">Nenhuma imagem cadastrada.</span>';
            }
            $('#viewImagens').html(imgHtml);

            $('#modalVisualizar').modal('show');
        });
    });

    // ========== EDITAR ==========
    $(document).on('click', '.btn-editar', function() {
        var id = $(this).data('id');

        // Reset estado
        arquivosEditar = [];

        $.get('/produtos/' + id, function(data) {
            var p = data.produto;

            $('#editProduto').val(p.produto);
            $('#editCategoria').val(p.id_categoria);
            $('#editFilial').val(p.id_filial);
            $('#editEstoque').val(p.qtd_estoque);

            var valorFormatado = parseFloat(p.valor).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $('#editValor').val(valorFormatado);

            // Montar galeria de imagens atuais
            var imgContainer = $('#editImagensAtuais');
            imgContainer.empty();
            $('#inputImagemPrincipal').val('');

            if (data.imagens && data.imagens.length > 0) {
                data.imagens.forEach(function(img) {
                    var isPrincipal = img.principal == 1;
                    var starClass = isPrincipal ? 'bi-star-fill text-warning' : 'bi-star text-white';
                    var itemClass = isPrincipal ? 'img-preview-item is-principal' : 'img-preview-item';

                    imgContainer.append(
                        '<div class="' + itemClass + ' img-atual" data-id="' + img.id + '">' +
                        '<img src="' + img.url + '" class="img-clickable">' +
                        '<span class="img-principal-star btn-set-principal" data-img-id="' + img.id + '"><i class="bi ' + starClass + '"></i></span>' +
                        '<div class="img-actions">' +
                        '<button type="button" class="btn btn-danger btn-remover-img" data-img-id="' + img.id + '"><i class="bi bi-x"></i></button>' +
                        '</div>' +
                        '<input type="hidden" name="imagens_remover[]" value="" disabled>' +
                        '</div>'
                    );
                });
            } else {
                imgContainer.html('<span class="text-muted small">Nenhuma imagem.</span>');
            }

            $('#previewEditar').empty();
            $('#editImagens').val('');
            atualizarContadorEditar();

            $('#formEditar').attr('action', '/produtos/' + id);
            $('#modalEditar').modal('show');
        });
    });

    // Definir imagem principal (editar - imagens atuais)
    $(document).on('click', '.btn-set-principal', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var imgId = $(this).data('img-id');

        // Reset todas
        $('#editImagensAtuais .img-preview-item').removeClass('is-principal');
        $('#editImagensAtuais .btn-set-principal i').removeClass('bi-star-fill text-warning').addClass('bi-star text-white');

        // Ativar esta
        $(this).closest('.img-preview-item').addClass('is-principal');
        $(this).find('i').removeClass('bi-star text-white').addClass('bi-star-fill text-warning');
        $('#inputImagemPrincipal').val(imgId);
    });

    // Remover imagem existente (editar)
    $(document).on('click', '.btn-remover-img', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var container = $(this).closest('.img-preview-item');
        var imgId = $(this).data('img-id');

        container.addClass('removida');
        container.find('input[name="imagens_remover[]"]').val(imgId).prop('disabled', false);
        container.find('.btn-remover-img').hide();
        container.find('.btn-set-principal').hide();

        container.find('.img-actions').append(
            '<button type="button" class="btn btn-success btn-desfazer-img" data-img-id="' + imgId + '"><i class="bi bi-arrow-counterclockwise"></i></button>'
        );

        atualizarContadorEditar();
    });

    // Desfazer remoção
    $(document).on('click', '.btn-desfazer-img', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var container = $(this).closest('.img-preview-item');

        container.removeClass('removida');
        container.find('input[name="imagens_remover[]"]').val('').prop('disabled', true);
        container.find('.btn-remover-img').show();
        container.find('.btn-set-principal').show();
        $(this).remove();

        atualizarContadorEditar();
    });

    // Limpar modal criar ao fechar
    $('#modalCriar').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $('#previewCriar').empty();
        arquivosCriar = [];
        principalCriarIndex = 0;
        $('#contadorImgCriar').text('0');
    });

    // ========== LIGHTBOX ==========
    $(document).on('click', '.img-clickable', function() {
        var src = $(this).attr('src');
        $('#lightboxImg').attr('src', src);
        $('#modalLightbox').modal('show');
    });
});
