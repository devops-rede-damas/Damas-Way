$(document).ready(function() {
    $('#tabelaCategorias').DataTable({
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
        $('#modalToggleStatusMsg').text('Tem certeza que deseja ' + acao + ' esta categoria?');
        $('#modalToggleStatus').modal('show');
    });

    $('#modalToggleStatusBtn').on('click', function() {
        if (!toggleAtual) return;

        var id = toggleAtual.data('id');
        toggleAtual.prop('checked', !toggleAtual.prop('checked'));

        $.ajax({
            url: '/categorias/' + id + '/toggle-status',
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
        var categoria = $(this).data('categoria');

        $('#editCategoria').val(categoria);
        $('#formEditar').attr('action', '/categorias/' + id);
        $('#modalEditar').modal('show');
    });
});
