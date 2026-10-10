$(function () {
  $("[data-busca-url]").each(function () {
    var $input = $(this);
    var url = $input.data("busca-url");
    var destino = String($input.data("busca-destino")).replace(/\/$/, "");

    $input.autocomplete({
      source: function (request, response) {
        $.getJSON(url, { term: request.term }).done(function (data) {
          if (data.length < 1) {
            data = [{ label: "Nenhum resultado encontrado", value: -1 }];
          }
          response(data);
        });
      },
      minLength: 1,
      select: function (event, ui) {
        if (ui.item.value == -1) {
          $(this).val("");
          return false;
        }
        window.location.href = destino + "/" + ui.item.id;
      },
    });
  });
});
