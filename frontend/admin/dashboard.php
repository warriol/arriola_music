<?php
/**
 * ARCHIVO: frontend/admin/dashboard.php
 * Descripción: Interfaz principal de administración con navegación actualizada.
 */

require_once '../../backend/autoload.php';

// Verificación de Sesión
$config = new class extends \class\Config {
    public function getKey() { return $this->secretKey; }
};
$sKey = $config->getKey();

if (!\class\Session::check($sKey)) {
    header('Location: login.php');
    exit();
}

$username = \class\Session::get('username');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Sintonía Artística</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        body { font-family: 'Courier Prime', monospace; background: #0f0f0f; color: #fdf2d9; }
        .admin-card { background: #1a0f08; border: 2px solid #4e342e; }
        .sidebar { background: linear-gradient(180deg, #2d1b0e 0%, #1a0f08 100%); border-right: 4px solid #111; }
        .nav-link { transition: all 0.3s ease; border-left: 4px solid transparent; }
        .nav-link:hover, .nav-link.active { background: #4e342e; border-left-color: #ff1a1a; color: #ffb347; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

<aside class="sidebar w-64 flex-shrink-0 flex flex-col">
    <div class="p-6 border-b border-[#4e342e]">
        <h2 class="text-xl font-bold text-amber-500 uppercase">Sintonía Admin</h2>
    </div>
    <nav class="flex-1 mt-4">
        <a href="dashboard.php" class="nav-link active block px-6 py-4 flex items-center gap-3"><span>📻</span> INICIO</a>
        <a href="tour.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>🗓️</span> GESTIÓN TOUR</a>
        <a href="galeria.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>📸</span> GALERÍA</a>
        <a href="configuracion.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>⚙️</span> AJUSTES</a>
        <a href="usuarios.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>👥</span> USUARIOS</a>
    </nav>
    <div class="p-6 border-t border-[#4e342e]">
        <a href="../../backend/auth/logout" class="block w-full bg-red-900/30 text-red-500 text-center py-2 rounded text-xs font-bold transition-colors">LOGOUT</a>
    </div>
</aside>

<main class="flex-1 overflow-y-auto p-8 bg-[#0a0a0a]">
    <header class="mb-10">
        <h1 class="text-4xl font-bold text-amber-500 mb-2">BIENVENIDO AL PANEL</h1>
        <p class="text-zinc-400">Desde aquí puedes modular la información de tu sitio oficial.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="admin-card p-6 rounded-lg shadow-xl">
            <h3 class="font-bold text-amber-200 uppercase tracking-widest text-sm mb-4">Próximos Toques</h3>
            <p class="text-3xl font-bold mb-4">--</p>
            <a href="tour.php" class="text-xs font-bold text-amber-500 hover:underline">GESTIONAR FECHAS →</a>
        </div>

        <div class="admin-card p-6 rounded-lg shadow-xl">
            <h3 class="font-bold text-amber-200 uppercase tracking-widest text-sm mb-4">Fotos en Archivo</h3>
            <p class="text-3xl font-bold mb-4">--</p>
            <a href="galeria.php" class="text-xs font-bold text-amber-500 hover:underline">GESTIONAR GALERÍA →</a>
        </div>

        <div class="admin-card p-6 rounded-lg shadow-xl border-l-4 border-green-600">
            <h3 class="font-bold text-amber-200 uppercase tracking-widest text-sm mb-4">Estado del Sitio</h3>
            <p class="text-xl font-bold mb-2 text-green-500">SINTONIZADO</p>
            <p class="text-[10px] text-zinc-500 italic">Conectado como: <?php echo $username; ?></p>
        </div>
    </div>
</main>
</body>
</html>