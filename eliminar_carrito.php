<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_carrito'])) {
    $id_carrito = intval($_POST['id_carrito']);
    $id_usuario = $_SESSION['id_usuario'];

    // Verifica que el producto pertenece al usuario
    $sql = "DELETE FROM carrito WHERE id_carrito = ? AND id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ii', $id_carrito, $id_usuario);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Producto eliminado del carrito.";
    } else {
        $_SESSION['error'] = "Error al eliminar el producto del carrito.";
    }
    $stmt->close();
    $conexion->close();
}

header("Location: carrito.php");
exit();
