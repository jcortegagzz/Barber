<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit();
}

// Redirigir según el rol del usuario
$rol = $_SESSION['rol'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BARBER TC - Menú</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
    /* Estilos generales */
    body, html {
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
        background-color: #1a1a1a;
        color: #fff;
        scroll-behavior: smooth;
    }

    /* Encabezado */
    .header {
        background-color: #1a1a1a;
        text-align: center;
        padding: 20px 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    }
    .header h1 {
        margin: 0;
        font-size: 2.5em;
        color: #ff4500;
    }
    .social-links {
        margin-top: 10px;
    }
    .social-links a {
        margin: 0 10px;
        color: #ff4500;
        text-decoration: none;
        font-size: 1.2em;
    }
    .social-links a:hover {
        color: #fff;
        transition: color 0.3s ease;
    }
    .logo-image {
        width: 150px;
        height: auto;
        margin: 20px auto;
    }

    /* Botones principales */
    .btn-container {
        text-align: center;
        margin-top: 30px;
    }
    .btn-container a {
        display: inline-block;
        margin: 10px;
        padding: 15px 30px;
        background-color: #ff4500;
        color: #fff;
        font-size: 1.2em;
        font-weight: bold;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-container a:hover {
        background-color: #e63e00;
        transform: scale(1.1);
    }

    /* Servicios */
    .services-title {
        text-align: center;
        font-size: 2em;
        color: #ff4500;
        margin: 40px 0 20px;
    }
    .services {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background-color: #202020;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    }
    .service-category h3 {
        font-size: 1.8em;
        color: #ff4500;
        border-bottom: 2px solid #444;
        padding-bottom: 10px;
    }
    .service-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 1.2em;
        border-bottom: 1px solid #444;
    }
    .service-item:last-child {
        border-bottom: none;
    }
    .service-item span {
        color: #ff4500;
        font-weight: bold;
    }

    /* Pie de página */
    .footer {
        background-color: #111;
        text-align: center;
        padding: 20px;
        color: #fff;
        font-size: 0.9em;
        margin-top: 20px;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.5);
    }

    /* Galería */
    .gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        padding: 20px;
    }
    .gallery-item {
        position: relative;
        width: 250px;
        height: 250px;
        overflow: hidden;
        border-radius: 10px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        transition: transform 0.3s ease;
        background-color: #202020; /* Fondo consistente para imágenes faltantes */
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        opacity: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.3s ease;
    }
    .gallery-item:hover .overlay {
        opacity: 1;
    }
    .overlay span {
        color: #fff;
        font-size: 1.8em;
        font-weight: bold;
    }
</style>

</head>
<body>
    <!-- Encabezado -->
<header class="header">
    <img src="IMAGENES/barbertclgo.jpg" alt="Logo Barber TC" class="logo-image">
    <h1>¡Bienvenido a Barber TC!</h1>
    <div class="social-links">
        <a href="#">Facebook</a>
        <a href="https://www.instagram.com/barberiatc_pro" target="_blank">Instagram</a>
        <a href="https://x.com/Barbertc_pro?t=-Yl3zwJxLVB-tBiiWB3xow&s=09" target="_blank">Twitter</a>

    </div>
</header>

<!-- Menú principal -->
<div class="btn-container">
    <?php if ($rol === 'admin'): ?>
        <!-- Botones para Admin -->
        <a href="admin_dashboard.php">Dashboard Admin</a>
        <a href="gestion_usuarios.php">Gestión de Usuarios</a>
        <a href="gestion_productos.php">Gestión de Productos</a>
        <a href="estadisticas.php">Estadísticas</a>
        <a href="logout.php">Cerrar Sesión</a>
    <?php elseif ($rol === 'empleado'): ?>
        <!-- Botones para Empleado -->
        <a href="empleado_dashboard.php">Dashboard Empleado</a>
        <a href="logout.php">Cerrar Sesión</a>
    <?php else: ?>
        <!-- Botones para Cliente -->
        <a href="perfil.php">Mi Perfil</a>
        <a href="citas.php">Agenda tu cita</a>
        <a href="productos.php">Productos</a>
        <a href="historial_compras.php">Historial de Compras</a>
        <a href="carrito.php">Carrito</a>

<a href="wishlist.php" class="btn">Wishlist</a>

        <a href="logout.php">Cerrar Sesión</a>
    <?php endif; ?>
</div>
    
    <!-- Título de Servicios -->
    <h2 class="services-title">Nuestros Servicios y Precios</h2>

    <!-- Servicios -->
    <section class="services">
    <div class="service-category">
        <h3>GROOMING</h3>
        <div class="service-item">
            <span>Corte de Cabello</span>
            <span>$380</span>
        </div>
        <div class="service-item">
            <span>Corte de Niño</span>
            <span>$320</span>
        </div>
        <div class="service-item">
            <span>Greca Sencilla</span>
            <span>$500</span>
        </div>
        <div class="service-item">
            <span>Limpieza de Contorno</span>
            <span>$180</span>
        </div>
        <div class="service-item">
            <span>Afeitado de Cabeza</span>
            <span>$380</span>
        </div>
    </div>

    <div class="service-category">
        <h3>SPA</h3>
        <div class="service-item">
            <span>Manicure</span>
            <span>$200</span>
        </div>
        <div class="service-item">
            <span>Pedicure</span>
            <span>$330</span>
        </div>
        <div class="service-item">
            <span>Limpieza Facial</span>
            <span>$250</span>
        </div>
        <div class="service-item">
            <span>Depilación (Cejas, Nariz y Oídos)</span>
            <span>$180</span>
        </div>
        <div class="service-item">
            <span>Masaje Anti-Estrés</span>
            <span>$150</span>
        </div>
    </div>

    <div class="service-category">
        <h3>PAQUETES</h3>
        <div class="service-item">
            <span>Scalper Grooming</span>
            <span>$595</span>
        </div>
        <div class="service-item">
            <span>Scalper Spa</span>
            <span>$450</span>
        </div>
        <div class="service-item">
            <span>Scalper Man</span>
            <span>$800</span>
        </div>
    </div>

    <!-- Nueva sección: BARBA -->
    <div class="service-category">
        <h3>BARBA</h3>
        <div class="service-item">
            <span>Recorte Alineado de Barba o Bigote</span>
            <span>$340</span>
        </div>
        <div class="service-item">
            <span>Afeitado de Barba</span>
            <span>$340</span>
        </div>
        <div class="service-item">
            <span>Coloración</span>
            <span>$300</span>
        </div>
    </div>
</section>
    <!-- Título de la galería -->
    <h2 class="gallery-title">¡Escoge tu tipo de corte favorito!</h2>

    <!-- Galería -->
    <section class="gallery">
        <div class="gallery-item">
            <img src="IMAGENES/TaperFade.jpeg" alt="TaperFade">
            <div class="overlay"><span>Taper Fade</span></div>
        </div>
        <div class="gallery-item">
            <img src="IMAGENES/LowFade.jpeg" alt="Low Fade">
            <div class="overlay"><span>Low Fade</span></div>
        </div>
        <div class="gallery-item">
            <img src="IMAGENES/MidFade.jpeg" alt="Mid Fade">
            <div class="overlay"><span>Mid Fade</span></div>
        </div>
        <div class="gallery-item">
            <img src="IMAGENES/BuzzCut.jpeg" alt="Buzz Cut">
            <div class="overlay"><span>Buzz Cut</span></div>
        </div>
        <div class="gallery-item">
            <img src="IMAGENES/FreshCrop.jpeg" alt="Fresh Crop">
            <div class="overlay"><span>Fresh Crop</span></div>
        </div>
        <div class="gallery-item">
            <img src="IMAGENES/Mullet.jpeg" alt="Mullet">
            <div class="overlay"><span>Mullet</span></div>
        </div>
        <div class="gallery-item">
            <img src="IMAGENES/Mohicano.jpeg" alt="Mohicano">
            <div class="overlay"><span>Mohicano</span></div>
        </div>
        <div class="gallery-item">
            <img src="IMAGENES/Undercut.jpeg" alt="Undercut">
            <div class="overlay"><span>Undercut</span></div>
        </div>
    </section>

    <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 BARBER TC - Todos los derechos reservados</p>
    </footer>
</body>
</html>
