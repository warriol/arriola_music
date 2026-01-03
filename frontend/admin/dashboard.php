<?php
/**
 * ARCHIVO: frontend/admin/dashboard.php
 * Descripción: Interfaz principal de administración con contadores dinámicos.
 */

require_once '../../backend/autoload.php';

// 1. Verificación de Sesión
$configClass = new class extends \class\Config {
    public function getKey() { return $this->secretKey; }
};
$sKey = $configClass->getKey();

if (!\class\Session::check($sKey)) {
    header('Location: login.php');
    exit();
}

$username = \class\Session::get('username');

// 2. Lógica para los contadores dinámicos
// Instanciamos los modelos para obtener las estadísticas
$tourModel = new Tour();
$eventos = $tourModel->listar(false); // Pasamos false para contar todos, incluso los no visibles
$totalEventos = is_array($eventos) ? count($eventos) : 0;

$galeriaModel = new Galeria();
$fotos = $galeriaModel->listar(false); // Pasamos false para contar todo el archivo
$totalFotos = is_array($fotos) ? count($fotos) : 0;
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
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-full bg-amber-700 flex items-center justify-center font-bold text-black text-xs">
                <?php echo strtoupper(substr($username, 0, 2)); ?>
            </div>
            <div class="text-xs">
                <p class="font-bold text-amber-200"><?php echo $username; ?></p>
                <p class="text-zinc-500">Administrador</p>
            </div>
        </div>
        <a href="../../backend/auth/logout" class="block w-full bg-red-900/30 hover:bg-red-900/50 text-red-500 text-center py-2 rounded text-xs font-bold transition-colors">
            APAGAR (LOGOUT)
        </a>
    </div>
</aside>

<main class="flex-1 overflow-y-auto p-8 bg-[#0a0a0a] relative">
    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/dark-leather.png');"></div>

    <header class="mb-10 relative z-10">
        <h1 class="text-4xl font-bold text-amber-500 mb-2">BIENVENIDO AL PANEL</h1>
        <p class="text-zinc-400">Estado actual de la frecuencia de Jose Luis Arriola.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">

        <!-- CARD TOUR -->
        <div class="admin-card p-6 rounded-lg shadow-xl">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-bold text-amber-200 uppercase tracking-widest text-sm">Próximos Toques</h3>
                <span class="text-2xl opacity-50">🗓️</span>
            </div>
            <p class="text-4xl font-bold mb-4 text-white">
                <?php echo $totalEventos; ?>
            </p>
            <a href="tour.php" class="text-[10px] font-bold text-amber-500 hover:text-amber-400 uppercase tracking-widest">
                Gestionar Fechas →
            </a>
        </div>

        <!-- CARD GALERÍA -->
        <div class="admin-card p-6 rounded-lg shadow-xl">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-bold text-amber-200 uppercase tracking-widest text-sm">Fotos en Archivo</h3>
                <span class="text-2xl opacity-50">📸</span>
            </div>
            <p class="text-4xl font-bold mb-4 text-white">
                <?php echo $totalFotos; ?>
            </p>
            <a href="galeria.php" class="text-[10px] font-bold text-amber-500 hover:text-amber-400 uppercase tracking-widest">
                Ir al Archivo →
            </a>
        </div>

        <!-- CARD ESTADO -->
        <div class="admin-card p-6 rounded-lg shadow-xl border-l-4 border-green-600">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-bold text-amber-200 uppercase tracking-widest text-sm">Estado del Sitio</h3>
                <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse shadow-[0_0_10px_#22c55e]"></span>
            </div>
            <p class="text-xl font-bold mb-2 text-green-500 uppercase">Sintonizado</p>
            <p class="text-[10px] text-zinc-500">Última actualización de señal: <?php echo date('d/m/Y'); ?></p>
        </div>

    </div>

    <div class="mt-10 admin-card p-6 rounded-lg relative z-10">
        <h3 class="font-bold text-amber-200 uppercase tracking-widest text-sm mb-4 border-b border-[#4e342e] pb-2">Bitácora de Sesión</h3>
        <ul class="text-xs space-y-3 text-zinc-400">
            <li class="flex gap-3">
                <span class="text-zinc-600"><?php echo date('H:i'); ?></span>
                <span>Consola de administración activa. Operador: <b><?php echo $username; ?></b></span>
            </li>
        </ul>
    </div>

</main>

</body>
</html>