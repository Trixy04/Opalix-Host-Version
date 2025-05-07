// public/js/auth.js

// Funzione per verificare se l'utente è autenticato
function checkAuth() {
    const token = localStorage.getItem('jwt');
  
    // Protezione per la home
    if (window.location.pathname.includes('index.html')) {
        if (!token) {
            window.location.href = 'login.html'; // se non loggato, redirect al login
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
      localStorage.setItem('jwt', data.token);
      window.location.href = 'index.html';
    } else {
      loginError.innerText = data.error || 'Errore imprevisto';
      loginError.style.display = 'block';
    }
  });
}


function logout() {
    localStorage.removeItem('jwt'); // Rimuove il token
    window.location.href = 'login.html'; // Reindirizza alla pagina di login
}

// Aggiungi il logout in un bottone di logout nella home o in altre pagine
const logoutButton = document.getElementById('logoutLink');
if (logoutButton) {
    logoutButton.addEventListener('click', logout);
}
