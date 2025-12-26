# Mapa de Estructura: Proyecto Sintonía Artística

Este documento sirve para rastrear la ubicación y función de cada archivo en la transición a PHP.

### 📂 Directorio Raíz

- index.php 
    - Punto de entrada principal (orquestador).

- .htaccess 
    - (Opcional) Para URLs amigables.

### 📂 /backend (Lógica y Datos)

- config/config.php 
    - Constantes globales (DB_HOST, DB_NAME, etc.).

- includes/db_connect.php 
    - Conexión PDO segura a MySQL.

- includes/functions.php 
    - Funciones reutilizables (limpiar strings, validar sesiones).

- auth/login_process.php 
    - Validación de credenciales.

- api/get_secciones.php 
    - Devuelve JSON con los datos para el dial.

- admin/ 
    - Carpeta con los archivos del CRUD (index.php, tour.php, galeria.php).

### 📂 /frontend (Presentación)

- assets/css/main.css 
    - Estilos extraídos del estilo original.

- assets/js/radio_logic.js 
    - Lógica del dial, aguja y sonidos.

- assets/js/gallery_slider.js 
    - Control de la galería de fotos.

- templates/header.php 
    - El gabinete de madera y el dial (reutilizable).

- templates/footer.php 
    - La placa metálica y créditos.

- vistas/ 
    - Partes dinámicas (seccion_inicio.php, seccion_tour.php, etc.).

### 📂 /media (Recursos)

- /img/ 
    - Fondos y fotos del artista.

- /music/ 
    - Archivos MP3 de las canciones.