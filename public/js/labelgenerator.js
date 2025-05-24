document.addEventListener("DOMContentLoaded", function () {
    const codiceInput = document.getElementById("codice");
    const prezzoInput = document.getElementById("prezzo_vendita");
    const nomeInput = document.getElementById("nome");

    const costoAcquistoInput = document.getElementById("prezzo_acquisto");
    const ricaricoInput = document.getElementById("ricarico_percentuale");

    const barcodeSvg = document.getElementById("barcode");
    const prezzoDiv = document.querySelector(".prezzo");
    const nomeArticoloDiv = document.getElementById("nome-articolo");

    let manualEdit = false;

    function aggiornaEtichetta() {
        const codice = codiceInput?.value || "000000";
        const prezzo = parseFloat(prezzoInput?.value) || 0;
        const nome = nomeInput?.value || "Nome Articolo";

        JsBarcode("#barcode", codice, {
            format: "CODE128",
            width: 2,
            height: 50,
            displayValue: true
        });

        prezzoDiv.textContent = `€ ${prezzo.toFixed(2)}`;
        nomeArticoloDiv.textContent = nome;
    }

    function aggiornaPrezzoDaCalcolo() {
        const costo = parseFloat(costoAcquistoInput?.value);
        const ricarico = parseFloat(ricaricoInput?.value);

        if (!isNaN(costo) && !isNaN(ricarico)) {
            const prezzoCalcolato = costo + (costo * ricarico / 100);
            manualEdit = false;
            prezzoInput.value = prezzoCalcolato.toFixed(2);
            aggiornaEtichetta(); // <-- FONDAMENTALE! Forza aggiornamento
        }
    }

    codiceInput.addEventListener("input", aggiornaEtichetta);
    nomeInput.addEventListener("input", aggiornaEtichetta);

    prezzoInput.addEventListener("input", () => {
        manualEdit = true;
        aggiornaEtichetta();
    });

    costoAcquistoInput.addEventListener("input", aggiornaPrezzoDaCalcolo);
    ricaricoInput.addEventListener("input", aggiornaPrezzoDaCalcolo);

    // aggiornamento iniziale
    aggiornaPrezzoDaCalcolo();
    aggiornaEtichetta();
});