<?php
$host = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "barber_system";

$conexion = new mysqli($host, $usuario, $clave, $baseDeDatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
} else {
    echo "Conexión exitosa a la base de datos";
}
?>
