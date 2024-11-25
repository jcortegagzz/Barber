<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'barber_system');

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Validar el producto que se va a añadir
$idProducto = $_POST['id_producto'];
$idUsuario = $_SESSION['id_usuario'];

// Verificar si el producto ya está en la lista de deseos
$sqlCheck = "SELECT * FROM wishlist WHERE id_usuario = ? AND id_producto = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("ii", $idUsuario, $idProducto);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    // Producto ya existe en la lista de deseos
    $_SESSION['wishlist_message'] = "El producto ya está en tu lista de deseos.";
} else {
    // Insertar el producto en la lista de deseos
    $sqlInsert = "INSERT INTO wishlist (id_usuario, id_producto) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ii", $idUsuario, $idProducto);

    if ($stmtInsert->execute()) {
        $_SESSION['wishlist_message'] = "Producto añadido a tu lista de deseos.";
    } else {
        $_SESSION['wishlist_message'] = "Error al añadir el producto a la lista de deseos.";
    }
}

// Redirigir de vuelta a la página anterior
header("Location: productos.php");
exit();
?>
