document.addEventListener('DOMContentLoaded', function () {
    fetch('api/articoli')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#tabella-articoli tbody');

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10">Nessun articolo trovato</td></tr>';
                return;
            }

            tbody.innerHTML = '';

            data.forEach(articolo => {
                const tr = document.createElement('tr');

                tr.innerHTML = `
                    
                    <td><input type="checkbox"></td>
                    <td><img src="${'./' + articolo.foto}" alt="foto articolo" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"></td>
                    <td>${articolo.codice_articolo}</td>
                    <td><a href="scheda_articolo?id=${articolo.id}">${articolo.nome_articolo}</a></td>
                    <td>${articolo.categoria}</td>
                    <td>${articolo.marca}</td>
                    <td>${articolo.materiale}</td>
                    <td>${articolo.carati_materiale || '-'}</td>
                    <td>${articolo.peso_materiale || '-'}</td>
                    <td>${articolo.stato}</td>
                    <td>
                        <button class="actions-btn btn-edit-articolo" style="margin-right: 15%" data-id="${articolo.id}"><i class="bi bi-pencil"></i></button>
                        <button class="actions-btn btn-pietre" style="margin-right: 15%" data-id="${articolo.id}"><i class="bi bi-gem"></i></button>
                        <button style="margin-right: 0%" class="actions-btn btn-print" data-id="${articolo.id}"><i class="bi bi-printer"></i></button>
                        
                    </td>
                    
                `;
                tbody.appendChild(tr);
            });

            // ✅ AGGIUNGI ORA GLI EVENT LISTENER

            // Stampa PDF
            document.querySelectorAll('.btn-print').forEach(btn => {
                btn.addEventListener('click', function () {
                    const articoloId = this.getAttribute('data-id');
                    if (articoloId) {
                        btn.disabled = true;
                        setTimeout(() => { btn.disabled = false }, 1000);

                        const popup = window.open(
                            `report/esporta_articolo_pdf.php?id=${articoloId}`,
                            'pdfPopup',
                            'width=900,height=700,scrollbars=yes,resizable=yes'
                        );
                        if (!popup || popup.closed || typeof popup.closed == 'undefined') {
                            alert("Popup bloccato dal browser. Abilita i popup per continuare.");
                        }
                    }
                });
            });


            // Modifica Articolo
            document.querySelectorAll('.btn-edit-articolo').forEach(btn => {
                btn.addEventListener('click', function () {
                    const articoloId = this.dataset.id;
                    console.log('ID articolo cliccato:', articoloId);

                    fetch(`api/articoli/${articoloId}`)
                        .then(response => response.json())
                        .then(data => {

                            // Popola campi
                            document.getElementById('articolo_id').value = data.id || '';
                            document.getElementById('codice').value = data.codice_articolo || '';
                            document.getElementById('nome').value = data.nome_articolo || '';
                            document.getElementById('descrizione').value = data.descrizione || '';
                            document.getElementById('categoria_id').value = data.categoria_id;
                            document.getElementById('marca_id').value = data.marca_id;
                            popolaSelect(`materiale_id`, "/opalix_server/public/api/materiali", "nome", "id", data.materiale_id);
                            document.getElementById('peso_materiale').value = data.peso_materiale || '';
                            document.getElementById('carati_materiale').value = data.carati_materiale || '';
                            document.getElementById('prezzo_acquisto').value = data.prezzo_acquisto || '';
                            document.getElementById('prezzo_vendita').value = data.prezzo_vendita || '';
                            const quantitaInput = document.getElementById('quantita');
                            if (quantitaInput) quantitaInput.value = data.quantita || '';
                            else console.warn("Elemento con ID 'quantita' non trovato nel DOM.");
                            document.getElementById('ubicazione').value = data.ubicazione || '';

                            console.log(data.stato_id);
                            console.log(document.getElementsByName('disponibile')); // Controlla se c'è almeno un elemento


                            if (data.stato_id == 1 ? document.getElementById('disponibile').checked = true :
                                data.stato_id == 2 ? document.getElementById('non_disponibile').checked = true :
                                    document.getElementById('in_ordine').checked = true);


                            const modal = new bootstrap.Modal(document.getElementById('modalModificaArticolo'));
                            modal.show();
                        })
                        .catch(error => {
                            console.error('Errore nel recupero dati articolo:', error);
                            alert('Errore nel recupero dati articolo');
                        });
                });
            });
        })
        .catch(error => {
            console.error('Errore nel caricamento degli articoli:', error);
        });

});
