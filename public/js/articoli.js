 document.addEventListener("DOMContentLoaded", function () {
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
                    <td>${articolo.nome_articolo}</td>
                    <td>${articolo.categoria}</td>
                    <td>${articolo.marca}</td>
                    <td>${articolo.materiale}</td>
                    <td>${articolo.carati || '-'}</td>
                    <td>${articolo.peso || '-'}</td>
                    <td>${articolo.pietre || '-'}</td>
                    <td>${articolo.stato}</td>
                    <td><button class="actions-btn" data-id="${articolo.id}"><i class="bi bi-pencil"></i></button></td>
                `;
                        tbody.appendChild(tr);
                    });

                    const buttons = document.querySelectorAll('.actions-btn');
                    buttons.forEach(button => {
                        button.addEventListener('click', function () {
                            const articoloId = button.getAttribute('data-id');
                            const articolo = data.find(a => a.id == articoloId);

                            if (articolo) {
                                document.getElementById('codiceModal').value = articolo.codice_articolo;
                                document.getElementById('nomeModal').value = articolo.nome_articolo;
                                document.getElementById('descrizioneModal').value = articolo.descrizione;
                                document.getElementById('caratiModal').value = articolo.carati;
                                document.getElementById('pesoModal').value = articolo.peso;
                                document.getElementById('prezzoAcquistoModal').value = articolo.prezzo_acquisto;
                                document.getElementById('prezzoVenditaModal').value = articolo.prezzo_vendita;
                                document.getElementById('quantitaModal').value = articolo.quantita;
                                document.getElementById('ubicazioneModal').value = articolo.ubicazione;
                                document.getElementById('noteModal').value = articolo.note;

                                // Selects (assumendo che abbiano id coerenti con questi)
                                document.getElementById('categoriaModal').value = articolo.categoria_id;
                                document.getElementById('marcaModal').value = articolo.marca_id;
                                document.getElementById('materialeModal').value = articolo.materiale_id;
                                document.getElementById('statoModal').value = articolo.stato_id;

                                const modal = new bootstrap.Modal(document.getElementById('modalArticolo'));
                                modal.show();

                                const form = document.getElementById('formArticoloModal');
                                form.addEventListener('submit', function (event) {
                                    event.preventDefault();
                                    updateArticolo(articoloId);
                                });
                            }
                        });
                    });
                })
                .catch(error => {
                    console.error('Errore caricamento articoli:', error);
                });

            function updateArticolo(articoloId) {
                const articolo = {
                    codice_articolo: document.getElementById('codiceModal').value,
                    nome: document.getElementById('nomeModal').value,
                    descrizione: document.getElementById('descrizioneModal').value,
                    carati: parseFloat(document.getElementById('caratiModal').value),
                    peso: parseFloat(document.getElementById('pesoModal').value),
                    prezzo_acquisto: parseFloat(document.getElementById('prezzoAcquistoModal').value),
                    prezzo_vendita: parseFloat(document.getElementById('prezzoVenditaModal').value),
                    quantita: parseInt(document.getElementById('quantitaModal').value),
                    ubicazione: document.getElementById('ubicazioneModal').value,
                    note: document.getElementById('noteModal').value,
                    categoria_id: parseInt(document.getElementById('categoriaModal').value),
                    marca_id: parseInt(document.getElementById('marcaModal').value),
                    materiale_id: parseInt(document.getElementById('materialeModal').value),
                    stato_id: parseInt(document.getElementById('statoModal').value)
                };

                fetch(`/opalix_server/public/api/articoli/${articoloId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(articolo)
                })
                    .then(response => response.json())
                    .then(data => {
                        alert('Articolo aggiornato con successo!');
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalArticolo'));
                        modal.hide();
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Errore aggiornamento articolo:', error);
                        alert('Errore durante l\'aggiornamento');
                    });
            }
        });