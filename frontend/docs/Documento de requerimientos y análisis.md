# Documento de Requerimientos y Análisis: Sintonía Artística v2.0

## Proyecto: Sitio Web Oficial - Jose Luis Arriola

### Tecnología: PHP 8.x + MySQL + Tailwind CSS

### Arquitectura: Separación Backend/Frontend (Modular)

### 1. Arquitectura del Sistema

Para garantizar un código limpio y seguro, el proyecto se dividirá en dos grandes bloques:

- /frontend: Contiene las vistas del usuario (HTML/PHP), estilos (Tailwind), scripts de cliente y activos multimedia.

- /backend: Contiene la lógica de negocio, conexión a BD (PDO), procesamiento de formularios, gestión de sesiones y el panel de administración.

- /public: Carpeta de acceso público (index.php) que orquestará la carga del sitio.

### 2. Requerimientos Funcionales (RF)

#### RF1: Panel de Control (Backoffice Simplificado)

- Acceso: Sistema de Login seguro.

- Gestión de Tour: CRUD completo con opción "Visible" (si es falso, no aparece en el dial).

- Gestión de Galería: CRUD de imágenes con opción "Visible".

- Gestión de Usuarios: El administrador principal puede crear o eliminar otros usuarios con acceso al panel.

- Ajustes Globales: Interfaz para cambiar:

    - Estado del sitio (En mantenimiento / Activo).

    - Nombre del sitio y SEO Metadata.

    - Links de Redes Sociales (Instagram, YouTube, Face, Spotify, Linktree).

ID de video destacado de YouTube.

#### RF2: Sintonía Dinámica (Frontend)

#### Consumo de datos desde el backend para renderizar dinámicamente:

- Secciones y sus audios.

- Tabla de fechas (ordenada por fecha DESC).

- Cuadrícula de fotos.

### 3. Requerimientos de Seguridad y Comunicación

#### Comunicación Back-Front

- Prepared Statements: Uso obligatorio de PDO con sentencias preparadas para eliminar el riesgo de Inyección SQL.

- Tokens CSRF: Implementación de tokens de seguridad en todos los formularios del panel de control para evitar ataques de falsificación de peticiones.

- Validación Dual: Los datos se validan en el cliente (JS) para mejorar la UX y en el servidor (PHP) por seguridad.

- Hasing de Contraseñas: Uso de password_hash() con algoritmo BCRYPT para la tabla de usuarios.

### 4. Modelo de Datos (MySQL)

#### Tabla: usuarios

```sql
    id (INT, PK, AI)
    username (VARCHAR 50, UNIQUE)
    password (VARCHAR 255)
    email (VARCHAR 100)
    ultimo_login (DATETIME)
```

#### Tabla: configuracion (Key-Value Pair)

```sql
    clave (VARCHAR 50, PK) - ej: 'sitio_nombre', 'mantenimiento', 'youtube_id'
    valor (TEXT)
```

#### Tabla: tour

```sql
    id (INT, PK, AI)
    fecha (DATE)
    lugar (VARCHAR 100)
    descripcion (VARCHAR 255)
    direccion (VARCHAR 255)
    url_tickets (VARCHAR 255)
    hashtag (VARCHAR 50)
    visible (BOOLEAN, DEFAULT 1)
```

#### Tabla: galeria

```sql
    id (INT, PK, AI)
    url_imagen (VARCHAR 255)
    pie_de_foto (VARCHAR 100)
    orden (INT)
    visible (BOOLEAN, DEFAULT 1)
```

### 5. Especificaciones de Diseño (UI/UX)

    Paleta de Colores (Tokens)

    Madera Oscura: #2d1b0e (Primario fondo)

    Madera Clara: #4e342e (Contraste)

    Dial Background: #fdf2d9 (Papel vintage)

    Aguja / Acento: #ff1a1a (Rojo vivo)

    Brillo Dial: #ffb347 (Naranja ámbar)

#### Tipografía

Cuerpo y UI: Courier Prime, Monospace (Refuerza el concepto de máquina de escribir/radio antigua).

#### Esquema de Diseño

Header: Dial sintonizador siempre visible (Sticky).

Main: Secciones de ancho completo (100vh) con fondos parallax oscurecidos (75% black overlay).

Footer: Placa metálica con tornillos (metal-plate) para redes sociales.

### 6. Diagramas Lógicos

#### Diagrama de Comunicación (Flujo de Datos)

    Petición: El Cliente solicita index.php.

    Backend: db_connect.php inicia sesión PDO -> get_data.php consulta tablas configuracion, tour y galeria.

    Procesamiento: PHP genera el HTML inyectando los datos en las variables correspondientes.

    Respuesta: Se sirve el HTML final al navegador con la sintonía ya cargada.