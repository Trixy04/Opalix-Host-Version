document.addEventListener('DOMContentLoaded', () => {
    fetch('/api/clienti')
      .then(res => res.json())
      .then(data => {
        const tbody = document.querySelector('#tabella-clienti tbody');
        tbody.innerHTML = '';
  
        if (data.length === 0) {
          const row = document.createElement('tr');
          const cell = document.createElement('td');
          cell.textContent = 'Nessun cliente trovato.';
          cell.colSpan = 6;
          row.appendChild(cell);
          tbody.appendChild(row);
          return;
        }
  
        data.forEach(cliente => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${cliente.nome}</td>
            <td>${cliente.cognome}</td>
            <td>${cliente.via || '-'}</td>
            <td>${cliente.citta || '-'}</td>
            <td>${cliente.email || '-'}</td>
            <td>${cliente.telefono || '-'}</td>
          `;
          tbody.appendChild(row);
        });
      })
      .catch(err => console.error('Errore nel recupero dei clienti:', err));
  });
  