<?php
include('conexion.php');
session_start();

// Verificar si el usuario es admin
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    die("<script>alert('Acceso denegado. Solo administradores pueden acceder.'); window.location.href='login.html';</script>");
}

// Eliminar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    if ($stmt->execute()) {
        echo "<script>alert('Usuario eliminado exitosamente.'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar usuario.'); window.location.href='admin.php';</script>";
    }
}

// Cambiar estado de cita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_estado'])) {
    $id_cita = $_POST['id_cita'];
    $nuevo_estado = $_POST['nuevo_estado'];
    $sql = "UPDATE citas SET estado = ? WHERE id_cita = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_estado, $id_cita);
    if ($stmt->execute()) {
        echo "<script>alert('Estado de la cita actualizado.'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar estado de la cita.'); window.location.href='admin.php';</script>";
    }
}

// Eliminar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_producto'])) {
    $id_producto = $_POST['id_producto'];
    $sql = "DELETE FROM productos WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    if ($stmt->execute()) {
        echo "<script>alert('Producto eliminado exitosamente.'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar producto.'); window.location.href='admin.php';</script>";
    }
}

// Agregar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_producto'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $imagen = $_POST['imagen'];

    $sql = "INSERT INTO productos (nombre, precio, stock, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdis", $nombre, $precio, $stock, $imagen);
    if ($stmt->execute()) {
        echo "<script>alert('Producto agregado exitosamente.'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error al agregar producto.'); window.location.href='admin.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
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
            max-width: 1200px;
            margin: 40px auto;
            background-color: #222;
            padding: 30px;
            border-radius: 10px;
        }
        h2, h3 {
            text-align: center;
            color: #ff4500;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #1a1a1a;
            color: #fff;
        }
        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #444;
        }
        button {
            padding: 10px;
            background-color: #ff4500;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #e63e00;
        }
        label, input, select {
            display: block;
            margin: 10px 0;
            width: 100%;
            padding: 8px;
            color: #000;
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
        <h2>Panel de Administración</h2>

        <!-- Gestión de Usuarios -->
        <h3>Usuarios Registrados</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id_usuario, nombre, email, rol FROM usuarios";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id_usuario']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['rol']}</td>
                        <td>
                            <form method='POST' action='admin.php'>
                                <input type='hidden' name='id_usuario' value='{$row['id_usuario']}'>
                                <button type='submit' name='eliminar_usuario' style='background-color: red;'>Eliminar</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Gestión de Citas -->
        <h3>Citas de Clientes</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Cita</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT c.id_cita, u.nombre AS cliente, c.fecha, c.hora, c.servicio, c.estado
                        FROM citas c
                        JOIN usuarios u ON c.id_usuario = u.id_usuario";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id_cita']}</td>
                        <td>{$row['cliente']}</td>
                        <td>{$row['fecha']}</td>
                        <td>{$row['hora']}</td>
                        <td>{$row['servicio']}</td>
                        <td>{$row['estado']}</td>
                        <td>
                            <form method='POST' action='admin.php'>
                                <input type='hidden' name='id_cita' value='{$row['id_cita']}'>
                                <select name='nuevo_estado'>
                                    <option value='pendiente' " . ($row['estado'] == 'pendiente' ? 'selected' : '') . ">Pendiente</option>
                                    <option value='confirmada' " . ($row['estado'] == 'confirmada' ? 'selected' : '') . ">Confirmada</option>
                                    <option value='cancelada' " . ($row['estado'] == 'cancelada' ? 'selected' : '') . ">Cancelada</option>
                                </select>
                                <button type='submit' name='cambiar_estado'>Actualizar</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Gestión de Productos -->
        <h3>Productos</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id_producto, nombre, precio, stock FROM productos";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id_producto']}</td>
                        <td>{$row['nombre']}</td>
                        <td>$ {$row['precio']}</td>
                        <td>{$row['stock']}</td>
                        <td>
                            <form method='POST' action='admin.php'>
                                <input type='hidden' name='id_producto' value='{$row['id_producto']}'>
                                <button type='submit' name='eliminar_producto' style='background-color: red;'>Eliminar</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Agregar Producto -->
        <h3>Agregar Producto</h3>
        <form method="POST">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" step="0.01" required>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>
            <label for="imagen">Imagen (ruta):</label>
            <input type="text" id="imagen" name="imagen" required>
            <button type="submit" name="agregar_producto">Agregar Producto</button>
        </form>

        <!-- Estadísticas Globales -->
        <h3>Estadísticas Globales</h3>
        <?php
        // Número total de usuarios
        $sql = "SELECT COUNT(*) AS total_usuarios FROM usuarios";
        $result = $conn->query($sql);
        $total_usuarios = $result->fetch_assoc()['total_usuarios'];

        // Número total de citas
        $sql = "SELECT COUNT(*) AS total_citas FROM citas";
        $result = $conn->query($sql);
        $total_citas = $result->fetch_assoc()['total_citas'];

        // Número total de compras
        $sql = "SELECT COUNT(*) AS total_compras FROM compras";
        $result = $conn->query($sql);
        $total_compras = $result->fetch_assoc()['total_compras'];

        // Ganancias totales
        $sql = "SELECT SUM(precio) AS total_ganancias FROM compras";
        $result = $conn->query($sql);
        $total_ganancias = $result->fetch_assoc()['total_ganancias'] ?: 0;

        echo "<p><strong>Total de Usuarios Registrados:</strong> $total_usuarios</p>";
        echo "<p><strong>Total de Citas Registradas:</strong> $total_citas</p>";
        echo "<p><strong>Total de Compras Realizadas:</strong> $total_compras</p>";
        echo "<p><strong>Ganancias Totales:</strong> $" . number_format($total_ganancias, 2) . " MXN</p>";
        ?>
    </div>
</body>
</html>
