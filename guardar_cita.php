<?php
include('conexion.php');
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Debes iniciar sesión para agendar una cita.");
}

// Obtener datos del formulario
$id_usuario = $_SESSION['id_usuario'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$servicio = $_POST['servicio'];

// Verificar disponibilidad de la cita
$sql_verificar = "SELECT * FROM citas WHERE fecha = ? AND hora = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("ss", $fecha, $hora);
$stmt_verificar->execute();
$result = $stmt_verificar->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('La fecha y hora seleccionadas no están disponibles.'); window.history.back();</script>";
    exit();
}

// Insertar la cita en la base de datos
$sql = "INSERT INTO citas (id_usuario, fecha, hora, servicio, estado) VALUES (?, ?, ?, ?, 'pendiente')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $id_usuario, $fecha, $hora, $servicio);

if ($stmt->execute()) {
    echo "<script>alert('Cita agendada exitosamente.'); window.location.href = 'menu.html';</script>";
} else {
    echo "<script>alert('Error al agendar la cita.'); window.history.back();</script>";
}

$conn->close();
?>
