<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'empleado') {
    header("Location: login.html");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'barber_system');
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener información del empleado
$idEmpleado = $_SESSION['id_usuario'];

// Consultar citas asignadas
$sqlCitas = "SELECT citas.id_cita, usuarios.nombre AS cliente, citas.fecha, citas.hora, citas.servicio, citas.estado 
             FROM citas 
             INNER JOIN usuarios ON citas.id_usuario = usuarios.id_usuario
             ORDER BY citas.fecha ASC";
$resultCitas = $conn->query($sqlCitas);

// Obtener estadísticas
$sqlEstadisticas = "SELECT 
                        COUNT(*) AS total_citas,
                        SUM(CASE WHEN estado = 'confirmada' THEN 1 ELSE 0 END) AS citas_completadas,
                        SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) AS citas_pendientes
                    FROM citas";
$resultEstadisticas = $conn->query($sqlEstadisticas)->fetch_assoc();

// Registrar producto vendido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_producto'])) {
    $producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    $sqlRegistrar = "INSERT INTO ventas_productos (id_empleado, producto, cantidad, precio, fecha_venta) 
                     VALUES ('$idEmpleado', '$producto', '$cantidad', '$precio', NOW())";

    if ($conn->query($sqlRegistrar)) {
        $mensajeProducto = "Producto registrado exitosamente.";
    } else {
        $mensajeProducto = "Error al registrar producto: " . $conn->error;
    }
}

// Obtener reporte diario de productos vendidos
$sqlReporte = "SELECT producto, SUM(cantidad) AS total_cantidad, SUM(precio * cantidad) AS total_precio 
               FROM ventas_productos WHERE id_empleado = $idEmpleado AND DATE(fecha_venta) = CURDATE() 
               GROUP BY producto";
$resultReporte = $conn->query($sqlReporte);

// Obtener inventario
$sqlInventario = "SELECT nombre, stock, precio FROM productos";
$resultInventario = $conn->query($sqlInventario);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Empleado</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a1a;
            color: #fff;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            color: #ff4500;
        }
        .section {
            margin-top: 30px;
            background-color: #202020;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        .section h2 {
            margin-top: 0;
            color: #ff4500;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ff4500;
        }
        tr:nth-child(even) {
            background-color: #2a2a2a;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 5px;
            background-color: #2a2a2a;
            color: #fff;
        }
        .btn {
            padding: 10px 20px;
            background-color: #ff4500;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?> (Empleado)</h1>
        </div>

        <!-- Citas -->
        <div class="section">
            <h2>Todas las Citas</h2>
            <table>
                <tr>
                    <th>ID Cita</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                </tr>
                <?php while ($row = $resultCitas->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_cita']; ?></td>
                    <td><?php echo $row['cliente']; ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td><?php echo $row['hora']; ?></td>
                    <td><?php echo $row['servicio']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Estadísticas -->
        <div class="section">
            <h2>Estadísticas</h2>
            <p>Total de citas: <?php echo $resultEstadisticas['total_citas']; ?></p>
            <p>Citas confirmadas: <?php echo $resultEstadisticas['citas_completadas']; ?></p>
            <p>Citas pendientes: <?php echo $resultEstadisticas['citas_pendientes']; ?></p>
        </div>

        <!-- Tareas adicionales -->
        <div class="section">
            <h2>Tareas Adicionales</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="producto">Producto:</label>
                    <input type="text" id="producto" name="producto" required>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" required>
                </div>
                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="number" step="0.01" id="precio" name="precio" required>
                </div>
                <button type="submit" class="btn" name="registrar_producto">Registrar Producto</button>
            </form>
            <?php if (isset($mensajeProducto)) echo "<p>$mensajeProducto</p>"; ?>
        </div>

        <!-- Inventario -->
        <div class="section">
            <h2>Inventario</h2>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Stock</th>
                    <th>Precio</th>
                </tr>
                <?php while ($row = $resultInventario->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td>$<?php echo $row['precio']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
