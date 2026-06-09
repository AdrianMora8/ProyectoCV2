-- ============================================================
-- Base de datos del proyecto "Cuarto Visual" (tienda en línea)
-- Importar con: mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS cv CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cv;

-- --------------------------------------------------
-- Usuarios (clientes y administradores)
-- --------------------------------------------------
CREATE TABLE usuarios (
    id       INT(11) NOT NULL AUTO_INCREMENT,
    nombre   VARCHAR(100) NOT NULL,
    email    VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol      ENUM('admin', 'cliente') DEFAULT 'cliente',
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------
-- Productos de la tienda
-- --------------------------------------------------
CREATE TABLE productos (
    id          INT(11) NOT NULL AUTO_INCREMENT,
    nombre      VARCHAR(150) NOT NULL,
    descripcion TEXT DEFAULT NULL,
    precio      DECIMAL(10,2) NOT NULL,
    stock       INT(11) NOT NULL DEFAULT 0,
    imagen      VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------
-- Ventas (cabecera de cada compra confirmada)
-- --------------------------------------------------
CREATE TABLE ventas (
    id         INT(11) NOT NULL AUTO_INCREMENT,
    usuario_id INT(11) NOT NULL,
    fecha      DATETIME NOT NULL,
    total      DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id),
    KEY usuario_id (usuario_id),
    CONSTRAINT fk_ventas_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------
-- Detalle de venta (líneas de producto por venta)
-- --------------------------------------------------
CREATE TABLE detalle_venta (
    id              INT(11) NOT NULL AUTO_INCREMENT,
    venta_id        INT(11) NOT NULL,
    producto_id     INT(11) NOT NULL,
    cantidad        INT(11) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (id),
    KEY venta_id (venta_id),
    KEY producto_id (producto_id),
    CONSTRAINT fk_detalle_venta    FOREIGN KEY (venta_id)    REFERENCES ventas (id),
    CONSTRAINT fk_detalle_producto FOREIGN KEY (producto_id) REFERENCES productos (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Datos de prueba
-- ============================================================

-- Usuarios demo (mismas credenciales que se muestran en la pantalla de login):
--   Admin:   admin@tienda.com   / admin123
--   Cliente: cliente@tienda.com / cliente123
-- (las contraseñas están hasheadas con password_hash() de PHP / bcrypt)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@tienda.com',   '$2y$12$2GDTckY5Miorxf90xOU2vuIY1K.krzsh5VYQnIyNwa3lyjzEGH7eC', 'admin'),
('Cliente Demo',  'cliente@tienda.com', '$2y$12$joaAj9mXk2jDKwo7Hq110Oz44RExBusIpDE30ycRLDhBp8jNApWEW', 'cliente');

-- Productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES
('Camiseta Básica',     'Camiseta de algodón 100%',  15.99, 50, NULL),
('Pantalón Jeans',      'Jeans slim fit azul',       39.99, 30, NULL),
('Zapatillas Running',  'Zapatillas para correr',    59.99, 10, NULL),
('Chaqueta Deportiva',  'Chaqueta impermeable',      49.99, 15, NULL),
('Gorra Casual',        'Gorra ajustable',           12.99, 37, NULL);
