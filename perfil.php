<?php
include('conexion.php');
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    die("<script>alert('Acceso denegado. Debes iniciar sesión como cliente.'); window.location.href='login.html';</script>");
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del usuario
$sql = "SELECT nombre, email, foto_perfil FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Procesar actualización de datos personales
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_perfil'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $email, $id_usuario);
    if ($stmt->execute()) {
        echo "<script>alert('Perfil actualizado exitosamente.'); window.location.href='perfil.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el perfil.'); window.location.href='perfil.php';</script>";
    }
}

// Procesar cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cambiar_contrasena'])) {
    $contrasena_actual = $_POST['contrasena_actual'];
    $nueva_contrasena = $_POST['nueva_contrasena'];

    $sql = "SELECT password FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario_actual = $result->fetch_assoc();

    if (password_verify($contrasena_actual, $usuario_actual['password'])) {
        $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_BCRYPT);
        $sql = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nueva_contrasena_hash, $id_usuario);
        if ($stmt->execute()) {
            echo "<script>alert('Contraseña actualizada exitosamente.'); window.location.href='perfil.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar la contraseña.'); window.location.href='perfil.php';</script>";
        }
    } else {
        echo "<script>alert('La contraseña actual es incorrecta.'); window.location.href='perfil.php';</script>";
    }
}

// Procesar cancelación de citas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancelar_cita'])) {
    $id_cita = $_POST['id_cita'];
    $sql = "UPDATE citas SET estado = 'cancelada' WHERE id_cita = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cita);
    if ($stmt->execute()) {
        echo "<script>alert('Cita cancelada exitosamente.'); window.location.href='perfil.php';</script>";
    } else {
        echo "<script>alert('Error al cancelar la cita.'); window.location.href='perfil.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Estilos generales */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #222;
            padding: 30px;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #ff4500;
        }
        form, table {
            margin-top: 20px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #1a1a1a;
        }
        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #444;
        }
        label {
            margin-top: 10px;
        }
        .logout {
            text-align: right;
            margin-top: -30px;
        }
        .logout a {
            color: #ff4500;
            text-decoration: none;
            font-weight: bold;
        }
        .logout a:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="logout.php">Cerrar Sesión</a>
        </div>
        <h2>Mi Perfil</h2>
        <img src="<?php echo $usuario['foto_perfil']; ?>" alt="Foto de Perfil" style="width: 150px; height: 150px; border-radius: 50%; display: block; margin: 0 auto;">

        <!-- Datos Personales -->
        <form method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo $usuario['email']; ?>" required>
            <button type="submit" name="actualizar_perfil">Actualizar Perfil</button>
        </form>

        <!-- Cambiar Contraseña -->
        <h2>Cambiar Contraseña</h2>
        <form method="POST">
            <input type="password" name="contrasena_actual" placeholder="Contraseña Actual" required>
            <input type="password" name="nueva_contrasena" placeholder="Nueva Contraseña" required>
            <button type="submit" name="cambiar_contrasena">Cambiar Contraseña</button>
        </form>

        <!-- Mis Citas -->
        <h2>Mis Citas</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id_cita, fecha, hora, servicio, estado FROM citas WHERE id_usuario = ? ORDER BY fecha ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['fecha']}</td>
                        <td>{$row['hora']}</td>
                        <td>{$row['servicio']}</td>
                        <td>{$row['estado']}</td>
                        <td>";
                    if ($row['estado'] === 'pendiente') {
                        echo "<form method='POST' action='perfil.php'>
                            <input type='hidden' name='id_cita' value='{$row['id_cita']}'>
                            <button type='submit' name='cancelar_cita' style='background-color: red; color: white;'>Cancelar</button>
                        </form>";
                    } else {
                        echo "No disponible";
                    }
                    echo "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Estadísticas del Usuario -->
        <h2>Estadísticas</h2>
        <?php
        // Total de citas
        $sql = "SELECT COUNT(*) AS total_citas FROM citas WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $citas = $result->fetch_assoc()['total_citas'];

        // Total de compras
        $sql = "SELECT COUNT(*) AS total_compras FROM compras WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $compras = $result->fetch_assoc()['total_compras'];

        // Gasto acumulado
        $sql = "SELECT SUM(precio) AS total_gasto FROM compras WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $gasto_total = $result->fetch_assoc()['total_gasto'] ?: 0;

        echo "<p><strong>Total de Citas:</strong> $citas</p>";
        echo "<p><strong>Total de Compras:</strong> $compras</p>";
        echo "<p><strong>Gasto Total en Compras:</strong> $" . number_format($gasto_total, 2) . " MXN</p>";
        ?>
    </div>
</body>
</html>
