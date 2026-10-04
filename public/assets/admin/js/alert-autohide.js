(function () {
  function initAlerts() {
    document.querySelectorAll(".alert[data-autohide]").forEach(function (el) {
      // Evita registrar duas vezes se o partial for incluído mais de uma vez
      if (el.dataset.autohideBound) return;
      el.dataset.autohideBound = "1";

      var delay = parseInt(el.dataset.autohide, 10) || 7000;
      var timer = null;

      // JavaScript puro: não depende de jQuery nem do plugin de alert do Bootstrap
      function close() {
        clearTimeout(timer);
        el.style.transition = "opacity .3s ease";
        el.style.opacity = "0";
        el.classList.remove("show");
        setTimeout(function () {
          if (el.parentNode) el.parentNode.removeChild(el);
        }, 350);
      }

      function start() {
        clearTimeout(timer);
        timer = setTimeout(close, delay);
      }

      function stop() {
        clearTimeout(timer);
      }

      // Pausa enquanto o mouse estiver em cima
      el.addEventListener("mouseenter", stop);
      el.addEventListener("mouseleave", start);

      // Botão ✕ também fecha sem depender do Bootstrap
      var btn = el.querySelector(".alert-close");
      if (btn) btn.addEventListener("click", close);

      start();
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAlerts);
  } else {
    initAlerts();
  }
})();
