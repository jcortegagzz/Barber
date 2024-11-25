<?php
include('conexion.php');
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    die("<script>alert('Debes iniciar sesión para ver tu historial de compras.'); window.location.href='login.html';</script>");
}

$id_usuario = $_SESSION['id_usuario'];

// Consultar historial de compras
$sql = "
    SELECT c.fecha_compra, p.nombre AS producto, c.precio 
    FROM compras c
    INNER JOIN productos p ON c.id_producto = p.id_producto
    WHERE c.id_usuario = ?
    ORDER BY c.fecha_compra DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Estilo general */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #111;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .table-container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #222;
        }
        .footer {
            background-color: #111;
            text-align: center;
            padding: 20px;
            font-size: 0.9em;
            color: #fff;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Historial de Compras</h1>
    </header>

    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['fecha_compra']; ?></td>
                            <td><?php echo $row['producto']; ?></td>
                            <td>$<?php echo $row['precio']; ?> MXN</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No has realizado compras aún.</p>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>&copy; 2024 BARBER TC - Todos los derechos reservados</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>
