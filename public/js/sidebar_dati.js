function updateDateTime() {
    const now = new Date();
    const formatted = now.toLocaleString('it-IT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    document.getElementById('datetime').textContent = formatted;
}

updateDateTime();
setInterval(updateDateTime, 60000); // aggiorna ogni minuto