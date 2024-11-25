<?php
include('conexion.php');
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    die("<script>alert('Debes iniciar sesión para realizar una compra.'); window.location.href='login.html';</script>");
}

// Obtener datos del producto desde la URL
$producto = urldecode($_GET['producto']);
$precio = $_GET['precio'];
$id_usuario = $_SESSION['id_usuario'];

// Consultar el producto en la base de datos
$sql = "SELECT * FROM productos WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $producto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verificar stock
    if ($row['stock'] <= 0) {
        die("<script>alert('Lo sentimos, este producto no está disponible en stock.'); window.location.href='productos.php';</script>");
    }

    // Reducir el stock en la base de datos
    $nuevo_stock = $row['stock'] - 1;
    $sql_update = "UPDATE productos SET stock = ? WHERE id_producto = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ii", $nuevo_stock, $row['id_producto']);
    $stmt_update->execute();

    // Registrar la compra en una tabla "compras" (opcional)
    $sql_compra = "INSERT INTO compras (id_usuario, id_producto, fecha_compra, precio) VALUES (?, ?, NOW(), ?)";
    $stmt_compra = $conn->prepare($sql_compra);
    $stmt_compra->bind_param("iid", $id_usuario, $row['id_producto'], $precio);
    $stmt_compra->execute();

    // Confirmar la compra
    echo "<script>alert('Compra realizada exitosamente.'); window.location.href='productos.php';</script>";
} else {
    die("<script>alert('Producto no encontrado.'); window.location.href='productos.php';</script>");
}

$conn->close();
?>
