// Utility: Recupera token e info utente
function getAuthInfo() {
  return {
    token: localStorage.getItem('jwt'),
    nome: localStorage.getItem('nome'),
    cognome: localStorage.getItem('cognome')
  };
}

// Utility: Esegue logout
function logout() {
  localStorage.removeItem('jwt');
  localStorage.removeItem('nome');
  localStorage.removeItem('cognome');
  window.location.href = 'login';
}

// Utility: Redirect se loggato o no
function redirectIf(condition, target) {
  if (condition) {
    window.location.href = target;
  }
}

// Verifica autenticazione all'avvio
function checkAuth() {
  const { token, nome, cognome } = getAuthInfo();
  const path = window.location.pathname;

  const isHome = path.includes('index.html');
  const isLogin = path.endsWith('/login') || path.endsWith('/login.html');

  // Se sei sulla home ma non loggato → vai al login
  redirectIf(isHome && !token, 'login');

  // Se sei sulla login ma già loggato → vai alla home
  redirectIf(isLogin && token, 'index.html');

  // Mostra nome e cognome nella sidebar, se presenti
  if (isHome && nome && cognome) {
    const sidebarUser = document.getElementById('sidebarUser');
    if (sidebarUser) {
      sidebarUser.textContent = `${nome} ${cognome}`;
    }
  }
}

// Gestione login form
function setupLoginForm() {
  const loginForm = document.getElementById('loginForm');
  const loginError = document.getElementById('loginError');

  if (!loginForm) return;

  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try {
      const res = await fetch('/opalix_server/public/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });

      const data = await res.json();

      if (res.ok && data.token) {
        localStorage.setItem('jwt', data.token);
        localStorage.setItem('nome', data.nome);
        localStorage.setItem('cognome', data.cognome);
        window.location.href = 'index.html';
      } else {
        loginError.innerText = data.error || 'Errore imprevisto';
        loginError.style.display = 'block';
      }
    } catch (err) {
      loginError.innerText = 'Errore di rete o server non raggiungibile';
      loginError.style.display = 'block';
    }
  });
}

// Gestione logout
function setupLogout() {
  const logoutButtons = document.querySelectorAll('.logoutLink');
  logoutButtons.forEach(btn =>
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      logout();
    })
  );
}

// Avvio script
document.addEventListener('DOMContentLoaded', () => {
  checkAuth();
  setupLoginForm();
  setupLogout();
});
