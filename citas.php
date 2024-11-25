<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda tu Cita - Barbería</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="citas.css">
    <script>
        async function verificarDisponibilidad() {
            const fecha = document.getElementById("fecha").value;
            const hora = document.getElementById("hora").value;
            const local = document.getElementById("local").value;

            if (fecha && hora && local) {
                const respuesta = await fetch("verificar_disponibilidad.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ fecha, hora, local })
                });
                const resultado = await respuesta.json();

                if (!resultado.disponible) {
                    alert("La fecha y hora seleccionadas ya están ocupadas para este local. Elija otra combinación.");
                }
            }
        }

        function generarHoras() {
            const horaSelect = document.getElementById("hora");
            horaSelect.innerHTML = "";
            for (let i = 8; i <= 20; i++) { // De 8 AM a 8 PM
                horaSelect.innerHTML += `<option value="${i.toString().padStart(2, "0")}:00">${i}:00</option>`;
                horaSelect.innerHTML += `<option value="${i.toString().padStart(2, "0")}:30">${i}:30</option>`;
            }
        }

        document.addEventListener("DOMContentLoaded", generarHoras);
    </script>
    <style>
        /* Estilo simplificado por brevedad */
    </style>
</head>
<body>
    <header class="header">
        <h1>Agenda tu Cita</h1>
    </header>

    <section class="form-container">
        <h2>Completa tus datos</h2>
        <form action="guardar_cita.php" method="POST" onsubmit="return verificarDisponibilidad();">
            <div class="form-group">
                <label for="nombre">Nombre completo:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha de la cita:</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>
            <div class="form-group">
                <label for="hora">Hora de la cita:</label>
                <select id="hora" name="hora" required></select>
            </div>
            <div class="form-group">
                <label for="local">Selecciona el local:</label>
                <select id="local" name="local" required>
                    <option value="Local 1 - Centro">Local 1 - Centro</option>
                    <option value="Local 2 - Norte">Local 2 - Norte</option>
                    <option value="Local 3 - Sur">Local 3 - Sur</option>
                </select>
            </div>
            <div class="form-group">
                <label for="servicio">Servicio deseado:</label>
                <select id="servicio" name="servicio" required>
                    <optgroup label="GROOMING">
                        <option value="Corte de Cabello - $380">Corte de Cabello - $380</option>
                        <option value="Corte de Niño - $320">Corte de Niño - $320</option>
                        <option value="Greca Sencilla - $500">Greca Sencilla - $500</option>
                        <option value="Limpieza de Contorno - $180">Limpieza de Contorno - $180</option>
                        <option value="Afeitado de Cabeza - $380">Afeitado de Cabeza - $380</option>
                    </optgroup>
                    <optgroup label="SPA">
                        <option value="Manicure - $200">Manicure - $200</option>
                        <option value="Pedicure - $330">Pedicure - $330</option>
                        <option value="Limpieza Facial - $250">Limpieza Facial - $250</option>
                        <option value="Depilación (Cejas, Nariz y Oídos) - $180">Depilación (Cejas, Nariz y Oídos) - $180</option>
                        <option value="Masaje Anti-Estrés - $150">Masaje Anti-Estrés - $150</option>
                    </optgroup>
                    <optgroup label="PAQUETES">
                        <option value="Scalper Grooming - $595">Scalper Grooming - $595</option>
                        <option value="Scalper Spa - $450">Scalper Spa - $450</option>
                        <option value="Scalper Man - $800">Scalper Man - $800</option>
                    </optgroup>
                    <optgroup label="BARBA">
                        <option value="Recorte Alineado de Barba o Bigote - $340">Recorte Alineado de Barba o Bigote - $340</option>
                        <option value="Afeitado de Barba - $340">Afeitado de Barba - $340</option>
                        <option value="Coloración - $300">Coloración - $300</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Agendar Cita</button>
            </div>
        </form>
    </section>
        <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 BARBER TC - Todos los derechos reservados</p>
    </footer>
</body>
</html>
