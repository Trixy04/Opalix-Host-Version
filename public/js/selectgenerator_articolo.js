document.addEventListener("DOMContentLoaded", function () {
  /**
   * Popola dinamicamente un elemento select con dati da un'API
   * @param {string} selectId - ID dell'elemento select
   * @param {string} apiUrl - URL dell'API da cui prendere i dati
   * @param {string} labelKey - Chiave del testo da mostrare nell'opzione
   * @param {string} valueKey - Chiave del valore dell'opzione
   */
  function popolaSelect(selectId, apiUrl, labelKey, valueKey) {
    const select = document.getElementById(selectId);
    if (!select) {
      console.warn(`Elemento select con id '${selectId}' non trovato.`);
      return;
    }

    fetch(apiUrl)
      .then(res => {
        if (!res.ok) throw new Error("Errore risposta API");
        return res.json();
      })
      .then(data => {
        select.innerHTML = '<option selected>Seleziona</option>';
        data.forEach(item => {
          const option = document.createElement("option");
          option.value = item[valueKey];
          option.textContent = item[labelKey];
          select.appendChild(option);
        });
      })
      .catch(err => {
        console.error(`Errore nel caricamento di ${selectId}:`, err);
      });
  }

  // Esempi di uso
  popolaSelect("materiale_id", "/opalix_server/public/api/materiali", "nome", "id");
  popolaSelect("pietra_1", "/opalix_server/public/api/pietre", "nome", "id");
  popolaSelect("pietra_2", "/opalix_server/public/api/pietre", "nome", "id");
  popolaSelect("pietra_3", "/opalix_server/public/api/pietre", "nome", "id");
  popolaSelect("pietra_4", "/opalix_server/public/api/pietre", "nome", "id");
  popolaSelect("categoria_id", "/opalix_server/public/api/categorie", "nome", "id");
  popolaSelect("marca_id", "/opalix_server/public/api/marche", "nome", "id");
});



