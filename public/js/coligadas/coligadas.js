$(document).ready(function() {
    $('#tabelaColigadas').DataTable({
        scrollX: true,
        language: DT_LANG_PTBR,
        order: [[3, 'desc'], [1, 'asc']],
        pageLength: 25,
        columnDefs: [
            { orderable: false, targets: -1 },
            {
                targets: 3,
                orderDataType: 'dom-checkbox'
            }
        ]
    });

    $(document).on('change', '.toggle-status', function() {
        var id = $(this).data('id');
        var toggle = $(this);

        $.ajax({
            url: '/coligadas/' + id + '/toggle-status',
            type: 'PATCH',
            success: function(response) {
                // ok
            },
            error: function() {
                toggle.prop('checked', !toggle.prop('checked'));
                alert('Erro ao alterar status.');
            }
        });
    });
});
