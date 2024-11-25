<?php
$servername = "localhost";      // Cambia esto si tu servidor es diferente
$username = "root";             // Usuario de tu base de datos MySQL
$password = "";                 // Contraseña de tu base de datos
$dbname = "barber_system";      // Nombre de la base de datos que creaste

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
