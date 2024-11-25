<?php
include 'conexion.php'; // Conectar con la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar los datos enviados desde el formulario
    $nombre = isset($_POST['nombre']) ? trim(htmlspecialchars($_POST['nombre'])) : null;
    $email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;

    // Verificar que todos los campos estén llenos
    if (!$nombre || !$email || !$password || !$confirm_password) {
        echo "<script>alert('Por favor, completa todos los campos.'); window.history.back();</script>";
        exit();
    }

    // Validar que las contraseñas coincidan
    if ($password != $confirm_password) {
        echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
        exit();
    }

    // Verificar si el correo ya existe
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('El correo ya está registrado.'); window.history.back();</script>";
        exit();
    }

    // Registrar al usuario
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nombre, email, contraseña, fecha_registro) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registro exitoso.'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error al registrar. Inténtelo de nuevo.'); window.history.back();</script>";
    }
}
?>
