<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $id_usuario = $_SESSION['id_usuario'];

    // Verifica que el producto pertenece al usuario
    $sql = "DELETE FROM wishlist WHERE id = ? AND id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ii', $id, $id_usuario);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Producto eliminado de la lista de deseos.";
    } else {
        $_SESSION['error'] = "Error al eliminar el producto de la lista de deseos.";
    }
    $stmt->close();
    $conexion->close();
}

header("Location: wishlist.php");
exit();
