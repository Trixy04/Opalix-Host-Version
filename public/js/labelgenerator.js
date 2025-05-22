document.addEventListener("DOMContentLoaded", function () {
    const codiceInput = document.getElementById("codice");
    const prezzoInput = document.getElementById("prezzo_vendita");
    const nomeInput = document.getElementById("nome");

    const costoAcquistoInput = document.getElementById("costo_acquisto");
    const ricaricoInput = document.getElementById("ricarico_percentuale");

    const barcodeSvg = document.getElementById("barcode");
    const prezzoDiv = document.querySelector(".prezzo");
    const nomeArticoloDiv = document.getElementById("nome-articolo");

    let manualEdit = false;

    function aggiornaPrezzoDaCalcolo() {
        const costo = parseFloat(costoAcquistoInput?.value);
        const ricarico = parseFloat(ricaricoInput?.value);

        if (!isNaN(costo) && !isNaN(ricarico) && !manualEdit) {
            const prezzoCalcolato = costo + (costo * ricarico / 100);
            prezzoInput.value = prezzoCalcolato.toFixed(2);
            aggiornaEtichetta(); // <-- AGGIORNAMENTO MANUALE ETICHETTA!
        }
    }

    function aggiornaEtichetta() {
        const codice = codiceInput?.value || "000000";
        const prezzo = parseFloat(prezzoInput?.value) || 0;
        const nome = nomeInput?.value || "Nome Articolo";

        if (barcodeSvg) {
            JsBarcode("#barcode", codice, {
                format: "CODE128",
                width: 2,
                height: 50,
                displayValue: true
            });
        }

        if (prezzoDiv) prezzoDiv.textContent = `€ ${prezzo.toFixed(2)}`;
        if (nomeArticoloDiv) nomeArticoloDiv.textContent = nome;
    }

    if (codiceInput && prezzoInput && nomeInput) {
        codiceInput.addEventListener("input", aggiornaEtichetta);
        nomeInput.addEventListener("input", aggiornaEtichetta);

        prezzoInput.addEventListener("input", () => {
            manualEdit = true;
            aggiornaEtichetta();
        });

        if (costoAcquistoInput && ricaricoInput) {
            costoAcquistoInput.addEventListener("input", () => {
                manualEdit = false;
                aggiornaPrezzoDaCalcolo();
            });

            ricaricoInput.addEventListener("input", () => {
                manualEdit = false;
                aggiornaPrezzoDaCalcolo();
            });
        }

        aggiornaEtichetta(); // inizializzazione
    } else {
        console.error("Uno o più input non trovati nel DOM.");
    }
});
