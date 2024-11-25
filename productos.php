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

// Obtener productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #1a1a1a; color: white; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .product { display: inline-block; width: 23%; margin: 1%; background-color: #202020; padding: 10px; border-radius: 8px; text-align: center; }
        .product img { max-width: 100%; height: 150px; object-fit: cover; margin-bottom: 10px; }
        .btn { display: inline-block; margin-top: 5px; padding: 8px 12px; background-color: #ff4500; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #e63e00; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Productos</h1>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>">
                <h3><?php echo $row['nombre']; ?></h3>
                <p>$<?php echo number_format($row['precio'], 2); ?> MXN</p>
                <form method="POST" action="agregar_carrito.php">
                    <input type="hidden" name="id_producto" value="<?php echo $row['id_producto']; ?>">
                    <button type="submit" class="btn">Añadir al carrito</button>
                </form>
                <form method="POST" action="agregar_wishlist.php">
                    <input type="hidden" name="id_producto" value="<?php echo $row['id_producto']; ?>">
                    <button type="submit" class="btn">Añadir a la lista de deseos</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
