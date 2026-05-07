document.addEventListener("DOMContentLoaded", () => {
  const reserverenForm = document.getElementById("reserverenForm");
  const submitBtn = reserverenForm.querySelector('button[type="submit"]');

  if (reserverenForm) {
    reserverenForm.addEventListener("submit", function (e) {
      // BUG 4: Geen bescherming tegen dubbele klik
      // submitBtn.disabled = true; ← ontbreekt

      // BUG 12: Knop werkt niet, door per ongeluk foutieve disabler
      submitBtn.disabled = true;
      submitBtn.classList.add("disabled");
      submitBtn.textContent = "Verzenden...";

      // Tijdelijke simulatie van bug: formulier wordt niet verzonden
      // e.preventDefault(); // ← Dit blokkeert de daadwerkelijke verzending

      // BUG 18: Simulatie van een back-button issue op iOS
      if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        window.onpageshow = function (event) {
          if (event.persisted) {
            location.reload(); // geforceerde reload
          }
        };
      }
    });
  }
});