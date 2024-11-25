<?php
// Incluir la conexión a la base de datos
include('conexion.php');

// Array con los correos y contraseñas que deseas actualizar (puedes agregar más)
$usuarios = [
    'juan@example.com' => 'hashed_password1',
    'ana@example.com' => 'hashed_password2',
    'admin@example.com' => 'hashed_password3'
];

foreach ($usuarios as $email => $password) {
    // Generar el hash de la contraseña
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Actualizar la contraseña en la base de datos
    $sql = "UPDATE usuarios SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $email);
    
    if ($stmt->execute()) {
        echo "Contraseña actualizada para: $email<br>";
    } else {
        echo "Error actualizando contraseña para: $email<br>";
    }
}

$conn->close();
?>
