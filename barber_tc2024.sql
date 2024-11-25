-- Crear la base de datos con un nuevo nombre
CREATE DATABASE IF NOT EXISTS barber_system;
USE barber_system;

-- Crear tabla de usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'empleado', 'admin') NOT NULL
);

-- Crear tabla de citas
CREATE TABLE citas (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    servicio VARCHAR(100) NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- Crear tabla de productos
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL
);

-- Crear tabla de registro de actividades
CREATE TABLE registro_actividades (
    id_actividad INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    actividad TEXT NOT NULL,
    fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- Insertar usuarios
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Juan Pérez', 'juan@example.com', 'hashed_password1', 'cliente'),
('Ana López', 'ana@example.com', 'hashed_password2', 'empleado'),
('Admin User', 'admin@example.com', 'hashed_password3', 'admin');

-- Insertar productos
INSERT INTO productos (nombre, precio, stock) VALUES
('Cera para cabello', 120.50, 25),
('Shampoo anti caída', 200.00, 15),
('Peine profesional', 80.00, 50);

-- Insertar citas
INSERT INTO citas (id_usuario, fecha, hora, servicio, estado) VALUES
(1, '2024-11-25', '10:30:00', 'Corte de cabello', 'pendiente'),
(1, '2024-11-26', '14:00:00', 'Afeitado', 'confirmada');

-- Insertar actividades
INSERT INTO registro_actividades (id_usuario, actividad) VALUES
(1, 'Usuario solicitó una cita para Corte de cabello'),
(2, 'Empleado confirmó la cita de Afeitado');

SELECT * FROM productos;

ALTER TABLE productos ADD COLUMN imagen VARCHAR(255) AFTER stock;

UPDATE productos SET imagen = 'IMAGENES/CeraGel.jpeg' WHERE id_producto = 1;
UPDATE productos SET imagen = 'IMAGENES/Shampoo.jpeg' WHERE id_producto = 2;
UPDATE productos SET imagen = 'IMAGENES/Peine.jpeg' WHERE id_producto = 3;

INSERT INTO productos (nombre, precio, stock, imagen) VALUES
('Cera Mate', 99.99, 20, 'IMAGENES/CeraMate.jpeg'),
('Aceite para barba', 150.00, 10, 'IMAGENES/AceiteBarba.jpeg');

CREATE TABLE compras (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    fecha_compra DATETIME NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

ALTER TABLE usuarios ADD COLUMN foto_perfil VARCHAR(255) DEFAULT 'IMAGENES/default_user.png';

ALTER TABLE citas ADD COLUMN id_empleado INT NULL AFTER id_usuario;

SELECT * FROM citas WHERE id_empleado = 'ID_DEL_EMPLEADO';

SELECT * FROM citas;

SELECT citas.id_cita, usuarios.nombre AS cliente, citas.fecha, citas.hora, citas.servicio, citas.estado 
FROM citas 
INNER JOIN usuarios ON citas.id_usuario = usuarios.id_usuario
ORDER BY citas.fecha ASC;

CREATE TABLE ventas_productos (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    producto VARCHAR(255) NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    fecha_venta DATETIME NOT NULL,
    FOREIGN KEY (id_empleado) REFERENCES usuarios(id_usuario)
);

CREATE TABLE lista_deseos (
    id_lista INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    fecha_agregado DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (1, 2, 1);

INSERT INTO wishlist (id_usuario, id_producto) VALUES (1, 2);

SELECT * FROM carrito;
SELECT * FROM wishlist;
