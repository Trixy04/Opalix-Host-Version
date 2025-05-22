document.addEventListener("DOMContentLoaded", function () {
  const costoAcquistoInput = document.getElementById("prezzo_acquisto");
  const ricaricoInput = document.getElementById("ricarico_percentuale");
  const prezzoVenditaInput = document.getElementById("prezzo_vendita");

  function calcolaPrezzoVendita() {
    const costo = parseFloat(costoAcquistoInput.value);
    const ricarico = parseFloat(ricaricoInput.value);

    if (!isNaN(costo) && !isNaN(ricarico)) {
      const prezzoVendita = costo + (costo * ricarico / 100);
      prezzoVenditaInput.value = prezzoVendita.toFixed(2);
    } else {
      prezzoVenditaInput.value = "";
    }
  }

  // Ricalcola ogni volta che cambia uno dei due campi
  costoAcquistoInput.addEventListener("input", calcolaPrezzoVendita);
  ricaricoInput.addEventListener("input", calcolaPrezzoVendita);
});
