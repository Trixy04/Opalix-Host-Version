function stampaEtichettaEPL(codice, nome, prezzo) {
    const epl = `
N
q400
Q280,24

A100,20,0,4,1,1,N,"${nome}"
B100,60,0,1,2,4,60,N,"${codice}"
A140,130,0,3,1,1,N,"${codice}"
A120,180,0,4,1,1,N,"EUR ${prezzo}"

P1
`;

    console.log("EPL generato:\n" + epl);

    BrowserPrint.getDefaultDevice("printer", function(printer) {
        if (!printer) {
            alert("Stampante Zebra non trovata.");
            return;
        }

        printer.send(epl, function() {
            console.log("Etichetta inviata.");
        }, function(error) {
            console.error("Errore invio EPL:", error);
        });
    });
}



function stampaEtichetta() {
    const codice = document.getElementById("codice").value || "000000";
    const nome = document.getElementById("nome").value || "Articolo";
    const prezzo = parseFloat(document.getElementById("prezzo_vendita").value) || 0;

    stampaEtichettaEPL(codice, nome, prezzo.toFixed(2));
}
