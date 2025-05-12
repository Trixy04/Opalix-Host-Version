// public/js/auth.js

// Funzione per verificare se l'utente è autenticato
function checkAuth() {
  const token = localStorage.getItem('jwt');

  // Protezione per la home
  if (window.location.pathname.includes('index.html')) {
    if (!token) {
      window.location.href = 'login'; // se non loggato, redirect al login
    } else {
      // Se l'utente è loggato, recupera nome e cognome dal localStorage
      const nome = localStorage.getItem('nome');
      const cognome = localStorage.getItem('cognome');
      if (nome && cognome) {
        // Mostra il nome e cognome nella sidebar
        const sidebarUser = document.getElementById('sidebarUser');
        if (sidebarUser) {
          sidebarUser.innerHTML = `${nome} ${cognome}`;
        }
      }
    }
  }

  // Se siamo sulla pagina di login e l'utente è già loggato, redirigiamolo alla home
  if (window.location.pathname.includes('login.html') && token) {
    window.location.href = 'index.html'; // se già loggato, redirect alla home
  }
}

// Esegui la funzione all'avvio della pagina
checkAuth();

// Gestione del login nel form
const loginForm = document.getElementById('loginForm');
const loginError = document.getElementById('loginError'); // aggiunto riferimento

if (loginForm) {
  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    const res = await fetch('/opalix_server/public/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });

    const data = await res.json();

    if (res.ok && data.token) {
      // Salva il token, nome e cognome nel localStorage
      localStorage.setItem('jwt', data.token);
      localStorage.setItem('nome', data.nome);  // Salva nome
      localStorage.setItem('cognome', data.cognome);  // Salva cognome
      window.location.href = 'index.html';
    } else {
      loginError.innerText = data.error || 'Errore imprevisto';
      loginError.style.display = 'block';
    }
  });
}

// Gestione logout da più pulsanti con classe comune
document.addEventListener('DOMContentLoaded', function () {
  console.log("DOM fully loaded");
  const logoutButtons = document.querySelectorAll('.logoutLink');
  logoutButtons.forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      // Rimuovi token, nome e cognome dal localStorage
      localStorage.removeItem('jwt');
      localStorage.removeItem('nome');
      localStorage.removeItem('cognome');
      window.location.href = '/opalix_server/public/pages/login.html';
    });
  });
});
