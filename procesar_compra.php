<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'barber_system');
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el ID del usuario
$idUsuario = $_SESSION['id_usuario'];

// Consultar productos del carrito
$sql = "SELECT id_producto, cantidad FROM carrito WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

// Insertar en la tabla de compras
while ($row = $result->fetch_assoc()) {
    $idProducto = $row['id_producto'];
    $cantidad = $row['cantidad'];

    $insertSql = "INSERT INTO compras (id_usuario, id_producto, fecha_compra) VALUES (?, ?, NOW())";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ii", $idUsuario, $idProducto);
    $insertStmt->execute();
}

// Vaciar el carrito
$deleteSql = "DELETE FROM carrito WHERE id_usuario = ?";
$deleteStmt = $conn->prepare($deleteSql);
$deleteStmt->bind_param("i", $idUsuario);
$deleteStmt->execute();

// Redirigir a una página de confirmación
header("Location: compra_exitosa.php");
exit();
