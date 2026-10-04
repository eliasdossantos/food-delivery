(function ($) {
  "use strict";
  $(function () {
    $("#table-BR").DataTable({
      aLengthMenu: [
        [5, 10, 15, -1],
        [5, 10, 15, "Todos"],
      ],
      iDisplayLength: 10,
      language: {
        search: "",
        searchPlaceholder: "Pesquisar...",
        lengthMenu: "Mostrar _MENU_ registros",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 a 0 de 0 registros",
        infoFiltered: "(filtrado de _MAX_ registros no total)",
        zeroRecords: "Nenhum registro encontrado",
        paginate: {
          first: "Primeiro",
          last: "Último",
          next: "Próximo",
          previous: "Anterior",
        },
      },
    });

    $("#table-BR").each(function () {
      var datatable = $(this);
      var search_input = datatable
        .closest(".dataTables_wrapper")
        .find("div[id$=_filter] input");
      search_input.attr("placeholder", "Pesquisar");
      search_input.removeClass("form-control-sm");

      var length_sel = datatable
        .closest(".dataTables_wrapper")
        .find("div[id$=_length] select");
      length_sel.removeClass("form-control-sm");
    });
  });
})(jQuery);
