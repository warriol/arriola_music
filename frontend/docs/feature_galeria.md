# Cambiso en la base de datos

- Creación de la tabla `albumes` para organizar las fotos en álbumes.
- Modificación de la tabla `galeria` para incluir una referencia al álbum correspondiente.
- Inserción de un álbum por defecto y asignación de las fotos existentes a este álbum.

```SQL
-- 1. Crear la tabla de álbumes
CREATE TABLE IF NOT EXISTS `albumes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `descripcion` TEXT NULL,
    `visible` TINYINT(1) DEFAULT 1,
    `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Modificar la tabla galeria para asociarla a un álbum
ALTER TABLE `galeria` ADD COLUMN `album_id` INT NULL AFTER `id`;
ALTER TABLE `galeria` ADD CONSTRAINT `fk_galeria_album` FOREIGN KEY (`album_id`) REFERENCES `albumes`(`id`) ON DELETE CASCADE;

-- 3. Insertar un álbum por defecto para fotos existentes
INSERT INTO `albumes` (`nombre`, `descripcion`) VALUES ('General', 'Álbum por defecto para capturas varias');
UPDATE `galeria` SET `album_id` = (SELECT id FROM albumes LIMIT 1);
```

# Cambios en la carga de imagenes

- Actualización del script de subida de imágenes para permitir la asignación a un álbum específico.
- Reducción de tamaño máximo permitido por imagen a 1MB; redimensionando a 1600px de lado y reduciendo su calidad a 75%.
- Se tulizará la librería GD para el procesamiento de imágenes.

### Cambios en Frontend

- Se actualiza la vista de creacion y subida de imagenes para el admin, permitiendo crear nuevo album, subir una o varias fotos, si se suben varias fotos iran al msimo album.
- Se actualiza la vista de galeria para mostrar las fotos organizadas por albumes, al ahcer clic en un album se abre un modal que muestra las imagenes q este contiene.

### Cambios en Backend

- Se actualiza el modelo de galeria para manejar la creacion de albunes y subida de imagenes, por defecto se asignan al album "General".
- Se actualiza el controlador de galeria para gestionar la subida de imagenes a albunes especificos.