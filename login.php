<?php
include('conexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para obtener el usuario por email
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifica la contraseña
        if (password_verify($password, $row['password'])) {
            // Contraseña correcta
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['rol'] = $row['rol'];

            // Redirigir según el rol
            if ($_SESSION['rol'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: menu.php");
            }
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado.'); window.location.href='login.html';</script>";
    }
}
?>
