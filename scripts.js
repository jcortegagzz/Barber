function updateTime() {
    const now = new Date();
    const date = now.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    const time = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    document.getElementById('date').textContent = date;
    document.getElementById('time').textContent = time;
}

function mostrarBienvenida() {
    alert("Bienvenido a Barbería TC");
}

setInterval(updateTime, 1000);
