$(document).ready(function() {
    $('#tabelaNiveis').DataTable({
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
        // Reverte visualmente até confirmar
        toggleAtual.prop('checked', !toggleAtual.prop('checked'));

        var acao = toggleAtual.prop('checked') ? 'desativar' : 'ativar';
        $('#modalToggleStatusMsg').text('Tem certeza que deseja ' + acao + ' este nível?');
        $('#modalToggleStatus').modal('show');
    });

    $('#modalToggleStatusBtn').on('click', function() {
        if (!toggleAtual) return;

        var id = toggleAtual.data('id');
        // Inverte visualmente
        toggleAtual.prop('checked', !toggleAtual.prop('checked'));

        $.ajax({
            url: '/niveis/' + id + '/toggle-status',
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

    // Fechar modal sem confirmar - não precisa fazer nada pois já revertemos

    // Abrir modal de edição
    $(document).on('click', '.btn-editar', function() {
        var id = $(this).data('id');
        var nivel = $(this).data('nivel');

        $('#editNivel').val(nivel);
        $('#formEditar').attr('action', '/niveis/' + id);
        $('#modalEditar').modal('show');
    });
});
