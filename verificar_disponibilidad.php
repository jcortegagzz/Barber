<?php
header('Content-Type: application/json');

// Datos de conexión a la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'barberia';

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die(json_encode(['disponible' => false, 'error' => 'Error de conexión']));
}

// Leer datos del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);
$fecha = $data['fecha'];
$hora = $data['hora'];
$local = $data['local'];

// Comprobar disponibilidad
$sql = "SELECT COUNT(*) AS total FROM citas WHERE fecha = ? AND hora = ? AND local = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $fecha, $hora, $local);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['disponible' => $row['total'] == 0]);

$stmt->close();
$conn->close();
