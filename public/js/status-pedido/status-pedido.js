$(document).ready(function() {
    $('#tabelaStatusPedido').DataTable({
        scrollX: true,
        language: DT_LANG_PTBR,
        order: [[1, 'asc']],
        pageLength: 25,
        columnDefs: [{ orderable: false, targets: [-1, -2] }]
    });

    // Toggle status com confirmação
    var toggleAtual = null;

    $(document).on('change', '.toggle-status', function(e) {
        e.preventDefault();
        toggleAtual = $(this);
        toggleAtual.prop('checked', !toggleAtual.prop('checked'));

        var acao = toggleAtual.prop('checked') ? 'desativar' : 'ativar';
        $('#modalToggleStatusMsg').text('Tem certeza que deseja ' + acao + ' este status de pedido?');
        $('#modalToggleStatus').modal('show');
    });

    $('#modalToggleStatusBtn').on('click', function() {
        if (!toggleAtual) return;

        var id = toggleAtual.data('id');
        toggleAtual.prop('checked', !toggleAtual.prop('checked'));

        $.ajax({
            url: '/status-pedido/' + id + '/toggle-status',
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

    // Abrir modal de edição
    $(document).on('click', '.btn-editar', function() {
        var id = $(this).data('id');
        var nomeStatus = $(this).data('nome-status');
        var descricao = $(this).data('descricao');

        $('#editNomeStatus').val(nomeStatus);
        $('#editDescricao').val(descricao);
        $('#formEditar').attr('action', '/status-pedido/' + id);
        $('#modalEditar').modal('show');
    });
});
