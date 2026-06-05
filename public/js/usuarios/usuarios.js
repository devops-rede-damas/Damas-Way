$(document).ready(function() {
    $('#tabelaUsuarios').DataTable({
        scrollX: true,
        language: DT_LANG_PTBR,
        order: [[5, 'desc'], [2, 'asc']],
        pageLength: 25,
        columnDefs: [
            { orderable: false, targets: [-1, -2] },
            { targets: 5, orderDataType: 'dom-checkbox' }
        ]
    });

    // Toggle status com confirmação
    var toggleAtual = null;

    $(document).on('change', '.toggle-status', function(e) {
        e.preventDefault();
        toggleAtual = $(this);
        toggleAtual.prop('checked', !toggleAtual.prop('checked'));

        var acao = toggleAtual.prop('checked') ? 'desativar' : 'ativar';
        $('#modalToggleStatusMsg').text('Tem certeza que deseja ' + acao + ' este usuário?');
        $('#modalToggleStatus').modal('show');
    });

    $('#modalToggleStatusBtn').on('click', function() {
        if (!toggleAtual) return;

        var id = toggleAtual.data('id');
        toggleAtual.prop('checked', !toggleAtual.prop('checked'));

        $.ajax({
            url: '/usuarios/' + id + '/toggle-status',
            type: 'PATCH',
            success: function(response) {
                $('#modalToggleStatus').modal('hide');
            },
            error: function() {
                toggleAtual.prop('checked', !toggleAtual.prop('checked'));
                $('#modalToggleStatus').modal('hide');
                alert('Erro ao alterar status.');
            }
        });
    });

    // Visualizar usuário
    $(document).on('click', '.btn-visualizar', function() {
        var id = $(this).data('id');

        $.ajax({
            url: '/usuarios/' + id,
            type: 'GET',
            success: function(response) {
                var u = response.usuario;
                $('#viewChapa').text(u.chapa);
                $('#viewNome').text(u.nome);
                $('#viewEmail').text(u.email);
                $('#viewNivel').text(u.nivel ? u.nivel.nivel : '—');

                var filiaisHtml = '';
                if (response.filiais.length > 0) {
                    response.filiais.forEach(function(f) {
                        filiaisHtml += '<span class="badge bg-light text-dark border me-1 mb-1">' + f.id_rm + ' - ' + f.filial + '</span>';
                    });
                } else {
                    filiaisHtml = '<span class="text-muted small">Nenhuma filial vinculada</span>';
                }
                $('#viewFiliais').html(filiaisHtml);

                $('#modalVisualizar').modal('show');
            },
            error: function() {
                alert('Erro ao carregar dados do usuário.');
            }
        });
    });

    // Editar usuário
    $(document).on('click', '.btn-editar', function() {
        var id = $(this).data('id');

        $.ajax({
            url: '/usuarios/' + id,
            type: 'GET',
            success: function(response) {
                var u = response.usuario;
                $('#editChapa').val(u.chapa);
                $('#editNome').val(u.nome);
                $('#editEmail').val(u.email);
                $('#editNivelId').val(u.nivel_id);
                $('#editSenha').val('');
                $('#formEditar').attr('action', '/usuarios/' + id);

                // Marcar filiais vinculadas
                $('.edit-filial-check').prop('checked', false);
                $('#editarSelectAll').prop('checked', false);
                if (response.filiais.length > 0) {
                    response.filiais.forEach(function(f) {
                        $('#filial_editar_' + f.id).prop('checked', true);
                    });
                    // Atualizar estado do "selecionar todas"
                    syncSelectAll('#editarSelectAll', '.edit-filial-check');
                }

                $('#modalEditar').modal('show');
            },
            error: function() {
                alert('Erro ao carregar dados do usuário.');
            }
        });
    });

    // Selecionar todas - Modal Criar
    $('#criarSelectAll').on('change', function() {
        var checked = $(this).prop('checked');
        $('.criar-filial-check').prop('checked', checked);
    });

    $(document).on('change', '.criar-filial-check', function() {
        syncSelectAll('#criarSelectAll', '.criar-filial-check');
    });

    // Selecionar todas - Modal Editar
    $('#editarSelectAll').on('change', function() {
        var checked = $(this).prop('checked');
        $('.edit-filial-check').prop('checked', checked);
    });

    $(document).on('change', '.edit-filial-check', function() {
        syncSelectAll('#editarSelectAll', '.edit-filial-check');
    });

    // Reset ao abrir modal criar
    $('#modalCriar').on('show.bs.modal', function() {
        $('.criar-filial-check').prop('checked', false);
        $('#criarSelectAll').prop('checked', false);
    });

    function syncSelectAll(selectAllSelector, checkboxSelector) {
        var total = $(checkboxSelector).length;
        var checked = $(checkboxSelector + ':checked').length;
        $(selectAllSelector).prop('checked', total === checked);
        $(selectAllSelector).prop('indeterminate', checked > 0 && checked < total);
    }
});
