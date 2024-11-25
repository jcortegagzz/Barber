<?php
session_start();

// Verificar si el usuario es admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
require_once 'conexion.php';

// Obtener lista de usuarios
$usuarios = $conexion->query("SELECT id_usuario, nombre, correo FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>Gestión de Usuarios</h1>
        <a href="menu.php">Volver al Menú</a>
    </header>
    <section>
        <h2>Lista de Usuarios</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $usuario['id_usuario']; ?></td>
                        <td><?php echo $usuario['nombre']; ?></td>
                        <td><?php echo $usuario['correo']; ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>">Editar</a>
                            <a href="eliminar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
