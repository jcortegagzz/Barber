<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barber TC - Bienvenido</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #1a1a1a;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
            text-align: center;
        }
        .container {
            width: 350px;
            background: linear-gradient(145deg, #1a1a1a, #333);
            padding: 40px 20px;
            border-radius: 15px;
            position: relative;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .container:before {
            content: "";
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            z-index: -1;
            background: linear-gradient(90deg, red, blue, white, red, blue);
            background-size: 300% 300%;
            animation: led-border 3s infinite;
            border-radius: 15px;
        }
        @keyframes led-border {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        h1 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }
        p {
            font-size: 1em;
            margin-bottom: 20px;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .button {
            padding: 10px 20px;
            background: #ff4500;
            color: #fff;
            font-size: 1.2em;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .button:hover {
            background: #e63e00;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido a Barber TC</h1>
        <p>Por favor, selecciona una opción para continuar:</p>
        <div class="button-container">
            <a href="login.html" class="button">Iniciar Sesión</a>
            <a href="registro.html" class="button">Registrarse</a>
        </div>
    </div>
</body>
</html>
