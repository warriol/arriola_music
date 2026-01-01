Mapa de Estructura: Proyecto Sintonía Artística v2.3

📂 Directorio Raíz

index.php -> Frontend público.
.htaccess

📂 /backend (API & Admin Logic)

index.php -> Router (Entidad/Acción).

.htaccess, .env, autoload.php.

📂 class/

Config.php -> Base de datos y Desencriptación.

Session.php -> Gestión de sesiones y seguridad de tokens.

📂 controllers/

TourController.php

AuthController.php -> Login/Logout.

📂 models/

Tour.php

Usuario.php -> Consulta de credenciales.

📂 /frontend/admin (Interfaz de Gestión)

login.php -> Formulario de acceso.

dashboard.php -> Menú principal del panel.

tour.php -> Gestión visual de fechas.

galeria.php -> Gestión visual de fotos.

📂 /media

📂 img/, music/, galeria/.