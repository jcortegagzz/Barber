<?php
session_start();

// Verificar si el usuario es admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
require_once 'conexion.php';

// Consultar datos para estadísticas
$citas_por_mes = $conexion->query("SELECT MONTH(fecha) AS mes, COUNT(*) AS total FROM citas GROUP BY MONTH(fecha)");
$productos_mas_vendidos = $conexion->query("SELECT p.nombre, COUNT(c.id_producto) AS vendidos FROM compras c INNER JOIN productos p ON c.id_producto = p.id_producto GROUP BY c.id_producto ORDER BY vendidos DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Estadísticas</h1>
        <a href="menu.php">Volver al Menú</a>
    </header>
    <section>
        <h2>Citas por Mes</h2>
        <canvas id="citasPorMes"></canvas>
        <script>
            const ctx = document.getElementById('citasPorMes').getContext('2d');
            const citasPorMes = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [<?php while ($row = $citas_por_mes->fetch_assoc()) echo "'" . $row['mes'] . "',"; ?>],
                    datasets: [{
                        label: 'Citas',
                        data: [<?php while ($row = $citas_por_mes->fetch_assoc()) echo $row['total'] . ","; ?>],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>
    </section>
</body>
</html>
