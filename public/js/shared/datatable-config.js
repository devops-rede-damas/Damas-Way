// ========== DataTable Defaults ==========

// Plugin para ordenar por checkbox
$.fn.dataTable.ext.order['dom-checkbox'] = function(settings, col) {
    return this.api().column(col, { order: 'index' }).nodes().map(function(td) {
        return $(td).find('input[type="checkbox"]').prop('checked') ? 1 : 0;
    });
};

// Configuração padrão de idioma pt-BR
var DT_LANG_PTBR = {
    processing: "Processando...",
    search: "Pesquisar:",
    lengthMenu: "Mostrar _MENU_ registros",
    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
    infoEmpty: "Mostrando 0 a 0 de 0 registros",
    infoFiltered: "(filtrado de _MAX_ registros no total)",
    loadingRecords: "Carregando...",
    zeroRecords: "Nenhum registro encontrado",
    emptyTable: "Nenhum dado disponível na tabela",
    paginate: {
        first: "Primeiro",
        previous: "Anterior",
        next: "Próximo",
        last: "Último"
    },
    aria: {
        sortAscending: ": ordenar coluna de forma ascendente",
        sortDescending: ": ordenar coluna de forma descendente"
    }
};
