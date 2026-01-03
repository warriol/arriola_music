# Sintonía Artística - Jose Luis Arriola 📻

Sintonía Artística es la plataforma web oficial de Jose Luis Arriola, diseñada con un concepto inmersivo de "Radio Analógica". El sitio no es solo un portafolio, sino una experiencia sensorial donde el usuario sintoniza las diferentes facetas del artista (música, fechas, archivo visual) a través de un dial interactivo.

## Release Actual: v1.0.0 - Enero 2026 <img src="https://img.shields.io/badge/Front-Hecho-success"> <img src="https://img.shields.io/badge/Back-Hecho-success"> 

## Release Actual: v1.1.0 - Enero 2026 <img src="https://img.shields.io/badge/Front-Hecho-success"> <img src="https://img.shields.io/badge/Back-En Proceos-yellow">
### Release Notes:
- **Frontend**: Mejoras en la experiencia de usuario y optimización del reproductor de audio.
- **Backend**: Desarrollo en curso del sistema de subida masiva de imágenes, gestionando grupo de imagenes por galerias y carga dinamica (paginado) de imagens con el scroll.

## 🎨 El Concepto Artístico

El sitio web está construido sobre una metáfora visual y funcional de una estación de radio vintage.

- **Navegación por Sintonía**: El usuario navega por las secciones mediante el scroll, el cual desplaza una aguja física sobre un dial analógico.
- **Atmósfera Auditiva**: El paso entre secciones genera ruidos de estática y modulación, simulando la búsqueda de una frecuencia radial.
- **Estética Dark/Retro**: Uso de texturas de madera, cuero y luces LED para evocar equipos de audio de mediados del siglo XX.

## 🚀 Características Principales

### Para el Usuario (Frontend)
- **SPA (Single Page Application)**: Navegación fluida sin recargas de página.
- **Tour Dinámico**: Agenda de conciertos actualizada en tiempo real con estados (Pasado/Próximo).
- **Galería Multimedia**: Visor de imágenes estilo Polaroid con efectos de escala de grises y zoom.
- **Reproductor Atmosférico**: Gestión de audio inteligente que se activa con la interacción del usuario.

### Para el Administrador (Backend / CMS)
- **Dashboard Estadístico**: Visualización rápida de eventos y fotos en archivo.
- **Subida Masiva**: Herramienta para cargar múltiples imágenes simultáneamente con procesamiento automático.
- **Gestión de Usuarios**: Control total sobre los operadores de la estación (CRUD de usuarios y reset de claves).
- **Configuración Global**: Edición de redes sociales y video destacado de YouTube desde el panel.

## 🛠️ Stack Tecnológico

- **Frontend**: ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white) ![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=flat&logo=tailwindcss&logoColor=white) ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)
- **Backend**: ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white) (Arquitectura MVC simplificada)
- **Base de Datos**: ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white) (PDO)
- **Seguridad**: ![SHA512](https://img.shields.io/badge/SHA512-4CAF50?style=flat) ![CryptoJS](https://img.shields.io/badge/CryptoJS-FF6F00?style=flat)
- **Gráficos**: ![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=flat&logo=chartdotjs&logoColor=white)

## 🎹 Sobre el Artista: Jose Luis Arriola

Músico y compositor cuya propuesta viaja entre el rock, el candombe y la bossa nova. Su obra se caracteriza por una profunda sensibilidad artística y una búsqueda constante de sonidos orgánicos.

### Sintoniza su frecuencia en redes:
- 🔗 **Linktree**: Jose Arriola Oficial
- 📸 **Instagram**: [@arriola_musica](https://instagram.com/arriola_musica)
- ▶️ **YouTube**: [@arriola_musica](https://youtube.com/@arriola_musica)
- 🎧 **Spotify**: [Jose Luis Arriola](https://spotify.com)
- 👤 **Facebook**: [joselito11179](https://facebook.com/joselito11179)

## 💻 Instalación y Configuración

1. Clonar el repositorio.
2. Configurar la base de datos importando el esquema SQL adjunto.
3. Crear un archivo `.env` en el directorio `backend/config/` con los siguientes datos:

   ```env
   DB_HOST=tu_host
   DB_NAME=arriola_music
   DB_USER=tu_usuario
   DB_PASS=tu_password
   SECRET_KEY=clave_de_sesion_unica