<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Exitosa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #1a1a1a; color: #fff; text-align: center; padding: 50px; }
        .btn { background-color: #ff4500; color: #fff; padding: 10px 20px; border: none; text-decoration: none; display: inline-block; }
        .btn:hover { background-color: #e63e00; }
    </style>
</head>
<body>
    <h1>¡Gracias por tu compra!</h1>
    <p>Tu pedido ha sido procesado con éxito.</p>
    <a href="productos.php" class="btn">Volver a la tienda</a>
</body>
</html>
