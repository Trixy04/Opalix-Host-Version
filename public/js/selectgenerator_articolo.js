document.addEventListener("DOMContentLoaded", function () {
  /**
   * Popola dinamicamente un elemento select con dati da un'API
   * @param {string} selectId - ID dell'elemento select
   * @param {string} apiUrl - URL dell'API da cui prendere i dati
   * @param {string} labelKey - Chiave del testo da mostrare nell'opzione
   * @param {string} valueKey - Chiave del valore dell'opzione
   * @param {string|number|null} selectedValue - (opzionale) Valore da selezionare

   */
  function popolaSelect(selectId, apiUrl, labelKey, valueKey, selectedValue = null) {
    const select = document.getElementById(selectId);
    if (!select) {
      console.warn(`Elemento select con id '${selectId}' non trovato.`);
      return Promise.reject(`Elemento select con id '${selectId}' non trovato.`);
    }

    return fetch(apiUrl)  // **ritorna la Promise**
      .then(res => {
        if (!res.ok) throw new Error("Errore risposta API");
        return res.json();
      })
      .then(data => {
        select.innerHTML = ''; // pulisci

        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "Seleziona";
        defaultOption.disabled = false;
        if (!selectedValue || selectedValue === "null") {
          defaultOption.selected = true;
        }
        select.appendChild(defaultOption);

        data.forEach(item => {
          const option = document.createElement("option");
          option.value = item[valueKey];
          option.textContent = item[labelKey];
          if (selectedValue !== null && item[valueKey] == selectedValue) {
            option.selected = true;
          }
          select.appendChild(option);
        });
      })
      .catch(err => {
        console.error(`Errore nel caricamento di ${selectId}:`, err);
        throw err;  // rilancia l'errore per far fallire la Promise
      });
  }




  window.popolaSelect = popolaSelect;

  // Esempi di uso
  popolaSelect("materiale_id", "/opalix_server/public/api/materiali", "nome", "id");
  popolaSelect("pietra_1", "/opalix_server/public/api/pietre", "nome", "id");
  popolaSelect("pietra_2", "/opalix_server/public/api/pietre", "nome", "id");
  popolaSelect("pietra_3", "/opalix_server/public/api/pietre", "nome", "id");
  popolaSelect("pietra_4", "/opalix_server/public/api/pietre", "nome", "id");
  popolaSelect("categoria_id", "/opalix_server/public/api/categorie", "nome", "id");
  popolaSelect("marca_id", "/opalix_server/public/api/marche", "nome", "id");
  popolaSelect("fornitoreId", "/opalix_server/public/api/fornitori", "ragione_sociale", "id");
  popolaSelect("causaleId", "/opalix_server/public/api/causali", "descrizione", "id");
  popolaSelect("pagamentoId", "/opalix_server/public/api/pagamenti", "descrizione", "id");
  popolaSelect("ivaId", "/opalix_server/public/api/iva", "codice", "id");
  popolaSelect("tipoDtt", "/opalix_server/public/api/doc", "codice", "id");
  popolaSelect("magazzinoId", "/opalix_server/public/api/magazzini", "nome", "id");
});



