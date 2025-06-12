function stampaEtichettaZPL(codice, nome, prezzo) {
    const zpl = `
^XA
^PW400
^LL300
^LH0,0

^CF0,30
^FO20,20^FB360,2,0,C^FD${nome}^FS

^FO60,80
^BY2,2,60
^BCN,60,Y,N,N
^FD${codice}^FS

^CF0,25
^FO20,160^FB360,1,0,C^FD${codice}^FS

^FO20,200^GB360,1,1^FS  // linea sottile divisoria

^CF0,35
^FO20,220^FB360,1,0,C^FD€ ${prezzo}^FS

^XZ
`;

    console.log("ZPL generato:\n" + zpl);

    BrowserPrint.getDefaultDevice("printer", function(printer) {
        if (!printer) {
            alert("Stampante Zebra non trovata.");
            return;
        }

        printer.send(zpl, function() {
            console.log("Etichetta inviata.");
        }, function(error) {
            console.error("Errore invio ZPL:", error);
        });
    });
}

function stampaEtichetta() {
    const codiceElem = document.getElementById("codice");
    const nomeElem = document.getElementById("nome");
    const prezzoElem = document.getElementById("prezzo_vendita");

    const codice = codiceElem ? codiceElem.value : "000000";
    const nome = nomeElem ? nomeElem.value : "Articolo";
    const prezzo = prezzoElem ? parseFloat(prezzoElem.value) : 0;

    stampaEtichettaZPL(codice, nome, prezzo.toFixed(2));
}

function stampaEtichettaConDati(nome, prezzo, codice) {
    stampaEtichettaZPL(codice, nome, prezzo.toFixed(2));
}
