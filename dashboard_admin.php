<?php
session_start();

// Verificar si el usuario es admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
require_once 'conexion.php';

// Consultar estadísticas
$total_usuarios = $conexion->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
$total_citas = $conexion->query("SELECT COUNT(*) AS total FROM citas")->fetch_assoc()['total'];
$total_compras = $conexion->query("SELECT COUNT(*) AS total FROM compras")->fetch_assoc()['total'];
$ingresos = $conexion->query("SELECT SUM(precio) AS total FROM compras")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Dashboard Admin</h1>
        <a href="menu.php">Volver al Menú</a>
    </header>
    <section>
        <h2>Resumen General</h2>
        <ul>
            <li>Total de Usuarios: <strong><?php echo $total_usuarios; ?></strong></li>
            <li>Total de Citas Agendadas: <strong><?php echo $total_citas; ?></strong></li>
            <li>Total de Compras Realizadas: <strong><?php echo $total_compras; ?></strong></li>
            <li>Ingresos Totales: <strong>$<?php echo number_format($ingresos, 2); ?></strong></li>
        </ul>
    </section>
</body>
</html>
