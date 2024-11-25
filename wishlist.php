<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT w.id, p.nombre AS nombre_producto, p.precio, p.imagen
        FROM wishlist w
        INNER JOIN productos p ON w.id_producto = p.id_producto
        WHERE w.id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Lista de Deseos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Mi Lista de Deseos</h1>
        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['nombre_producto']; ?></td>
                    <td><?php echo number_format($row['precio'], 2); ?> MXN</td>
                    <td><img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre_producto']; ?>" width="50"></td>
                    <td>
                        <form action="eliminar_wishlist.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No tienes productos en tu lista de deseos.</p>
        <?php endif; ?>
    </div>
</body>
</html>
