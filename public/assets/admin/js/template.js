(function ($) {
  "use strict";
  $(function () {
    var body = $("body");
    var sidebar = $(".sidebar");

    // O item ativo do menu é definido no servidor (components/sidebar.php).
    // Não adicionar active via JS: comparar pedaços da URL marca vários itens ao mesmo tempo.

    // Fecha os outros submenus da sidebar ao abrir um
    sidebar.on("show.bs.collapse", ".collapse", function () {
      sidebar.find(".collapse.show").collapse("hide");
    });

    // Minimizar sidebar
    $('[data-toggle="minimize"]').on("click", function () {
      body.toggleClass("sidebar-icon-only");
    });

    // Checkbox e radios
    $(".form-check label,.form-radio label").append(
      '<i class="input-helper"></i>',
    );

    // Banner "pro" do tema (só roda se o elemento existir no layout)
    var proBanner = document.querySelector("#proBanner");
    var bannerClose = document.querySelector("#bannerClose");

    if (proBanner && bannerClose) {
      if ($.cookie("majestic-free-banner") != "true") {
        proBanner.classList.add("d-flex");
      } else {
        proBanner.classList.add("d-none");
      }

      bannerClose.addEventListener("click", function () {
        proBanner.classList.add("d-none");
        proBanner.classList.remove("d-flex");
        var date = new Date();
        date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
        $.cookie("majestic-free-banner", "true", { expires: date });
      });
    }
  });
})(jQuery);
