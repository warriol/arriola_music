-- --------------------------------------------------------
-- Base de Datos: `sintonia_artistica`
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `sintonia_artistica` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sintonia_artistica`;

-- --------------------------------------------------------
-- Tabla: `usuarios` (Gestión de acceso al panel)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL, -- Contraseña hasheada con BCRYPT
    `email` VARCHAR(100) NULL,
    `ultimo_login` DATETIME NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Tabla: `configuracion` (Variables globales del sitio)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `configuracion` (
    `clave` VARCHAR(50) PRIMARY KEY,
    `valor` TEXT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Tabla: `secciones` (Control del Dial y Música)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `secciones` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(50) NOT NULL,
    `slug` VARCHAR(20) NOT NULL, -- s1, s2, s3, etc.
    `cancion_url` VARCHAR(255) NULL, -- Ruta al mp3 en /media/music/
    `orden` INT DEFAULT 0
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Tabla: `tour` (Presentaciones en vivo)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tour` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fecha` DATE NOT NULL,
    `lugar` VARCHAR(100) NOT NULL,
    `descripcion` VARCHAR(255) NULL,
    `direccion` VARCHAR(255) NULL,
    `url_tickets` VARCHAR(255) NULL,
    `hashtag` VARCHAR(50) NULL,
    `visible` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Tabla: `galeria` (Fotos tipo Polaroid)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `galeria` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `url_imagen` VARCHAR(255) NOT NULL,
    `pie_de_foto` VARCHAR(100) NULL,
    `orden` INT DEFAULT 0,
    `visible` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- DATOS INICIALES (SEMILLAS)
-- --------------------------------------------------------

-- Usuario administrador por defecto (password: admin123)
-- NOTA: En producción, esto debe cambiarse inmediatamente.
INSERT INTO `usuarios` (`username`, `password`, `email`) VALUES 
('admin', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'admin@example.com');

-- Configuración base
INSERT INTO `configuracion` (`clave`, `valor`) VALUES 
('nombre_sitio', 'Jose Luis Arriola - Sintonía Artística'),
('mantenimiento', '0'),
('youtube_id', 'LNGkfFc0Fpk'),
('instagram_url', 'https://www.instagram.com/josearriola_musico/'),
('youtube_url', 'https://www.youtube.com/@JoseLuisArriola'),
('facebook_url', 'https://www.facebook.com/josearriolamusico'),
('spotify_url', 'https://open.spotify.com/artist/47CJG3RNzKaw1WI5lQ55eB'),
('linktree_url', 'https://linktr.ee/Jose_Arriola');

-- Secciones predefinidas (Sintonía)
INSERT INTO `secciones` (`nombre`, `slug`, `cancion_url`, `orden`) VALUES 
('INICIO', 's1', 'cancion_01.mp3', 1),
('GALERÍA', 's2', 'cancion_02.mp3', 2),
('TOUR', 's3', 'cancion_03.mp3', 3),
('DISCOS', 's4', 'cancion_04.mp3', 4),
('MULTIMEDIA', 's5', 'cancion_05.mp3', 5),
('REDES', 's6', 'cancion_06.mp3', 6);