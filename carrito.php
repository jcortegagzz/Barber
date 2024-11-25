<?php
// Incluye el archivo de conexión
include 'conexion.php';

// Verifica si el usuario está autenticado
session_start();
if (!isset($_SESSION['id_usuario'])) {
    echo "Error: Usuario no autenticado.";
    exit();
}

$id_usuario = $_SESSION['id_usuario']; // ID del usuario actual

// Consulta para obtener los productos del carrito del usuario
$sql = "SELECT carrito.id_carrito, productos.nombre AS producto, productos.precio, carrito.cantidad, 
               (productos.precio * carrito.cantidad) AS subtotal 
        FROM carrito
        INNER JOIN productos ON carrito.id_producto = productos.id_producto
        WHERE carrito.id_usuario = $id_usuario";

$resultado = $conexion->query($sql);

$total = 0; // Inicializa el total
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ff4500;
        }
        tr:nth-child(even) {
            background-color: #333333;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            text-align: right;
        }
        .btn {
            background-color: #ff4500;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Carrito de Compras</h1>
    <?php if ($resultado->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['producto']; ?></td>
                        <td><?php echo number_format($row['precio'], 2); ?> MXN</td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td><?php echo number_format($row['subtotal'], 2); ?> MXN</td>
                        <td>
                            <a class="btn" href="eliminar_carrito.php?id=<?php echo $row['id_carrito']; ?>">Eliminar</a>
                        </td>
                    </tr>
                    <?php $total += $row['subtotal']; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p class="total">Total: <?php echo number_format($total, 2); ?> MXN</p>
        <a class="btn" href="procesar_compra.php">Procesar Compra</a>
    <?php else: ?>
        <p>No tienes productos en tu carrito.</p>
    <?php endif; ?>
</body>
</html>
