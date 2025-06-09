document.addEventListener('DOMContentLoaded', async () => {
    const params = new URLSearchParams(window.location.search);
    const articoloId = params.get('id');
    const token = localStorage.getItem('jwt');

    if (!articoloId || !token) return;

    try {
        const response = await fetch(`api/articoli/${articoloId}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        const data = await response.json();
        console.log(data)

        // Popola intestazione e descrizione
        const articolo = data;

        const ricarico = ((articolo.prezzo_vendita - articolo.prezzo_acquisto)/articolo.prezzo_acquisto)*100;

        document.getElementById('titolo').textContent = `# ${articolo.codice_articolo} ${articolo.nome_articolo}`;
        document.getElementById('nome_articolo').textContent = articolo.nome_articolo;
        document.querySelector('.col h5').textContent = articolo.nome_articolo;
        document.querySelector('.text-primary').textContent = `Cat. ${articolo.categoria} - Marchio: ${articolo.marca}`;
        document.getElementById('descrizione').textContent = articolo.descrizione;

        document.getElementById('prezzoAcquisto').textContent = `${articolo.prezzo_acquisto.toLocaleString()} €`;
        document.getElementById('ricarico').textContent = `+ ${ricarico.toLocaleString(undefined, { maximumFractionDigits: 2 })} %`;
        document.getElementById('prezzoVendita').textContent = `${articolo.prezzo_vendita.toLocaleString()} €`;

        document.getElementById('disponibilita').textContent = articolo.stato;
        document.getElementById('ubicazione').textContent = articolo.ubicazione;
        document.getElementById('quantita').textContent = articolo.quantita;

        // Pietre
        const pietreTable = document.querySelector('#pietre_tabella tbody');
        pietreTable.innerHTML = '';
        if (articolo.pietre) {
            articolo.pietre.forEach((pietra, index) => {
                const row = `<tr>
            <th scope="row">${index + 1}</th>
            <td>${pietra.nome}</td>
            <td class="text-center">${pietra.caratura}</td>
            <td class="text-center">${pietra.quantita}</td>
        </tr>`;
                pietreTable.insertAdjacentHTML('beforeend', row);
            });
        }




        /* Documenti
        const docTable = document.querySelectorAll('table')[1].querySelector('tbody');
        docTable.innerHTML = '';
        data.documenti.forEach((doc, index) => {
            const row = `<tr>
                <th scope="row">${index + 1}</th>
                <td colspan="2">${doc.nome}</td>
                <td><a href="${doc.link}" target="_blank">Apri</a></td>
            </tr>`;
            docTable.insertAdjacentHTML('beforeend', row);
        });
*/
    } catch (error) {
        console.error('Errore nel caricamento articolo:', error);
    }
});
