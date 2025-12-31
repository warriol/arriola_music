<?php
/**
 * ARCHIVO: frontend/admin/configuracion.php
 * Descripción: Panel de ajustes globales para el administrador.
 */

require_once '../../backend/autoload.php';

// Verificación de Sesión
$configClass = new class extends \class\Config {
    public function getKey() { return $this->secretKey; }
};
$sKey = $configClass->getKey();

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
    <title>Ajustes - Sintonía Artística</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../../media/img/favicon.png" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        body { font-family: 'Courier Prime', monospace; background: #0f0f0f; color: #fdf2d9; }
        .admin-card { background: #1a0f08; border: 2px solid #4e342e; }
        .sidebar { background: linear-gradient(180deg, #2d1b0e 0%, #1a0f08 100%); border-right: 4px solid #111; }
        .nav-link { transition: all 0.3s ease; border-left: 4px solid transparent; }
        .nav-link:hover, .nav-link.active { background: #4e342e; border-left-color: #ff1a1a; color: #ffb347; }

        input, textarea { background: #000 !important; border: 1px solid #4e342e !important; color: #fdf2d9 !important; padding: 12px; width: 100%; outline: none; transition: border-color 0.3s; }
        input:focus { border-color: #ffb347 !important; }
        label { display: block; font-size: 0.7rem; text-transform: uppercase; font-weight: bold; color: #8d6e63; margin-bottom: 5px; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

<aside class="sidebar w-64 flex-shrink-0 flex flex-col">
    <div class="p-6 border-b border-[#4e342e]">
        <h2 class="text-xl font-bold text-amber-500 uppercase">Sintonía Admin</h2>
    </div>
    <nav class="flex-1 mt-4">
        <a href="dashboard.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>📻</span> INICIO</a>
        <a href="tour.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>🗓️</span> GESTIÓN TOUR</a>
        <a href="galeria.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>📸</span> GALERÍA</a>
        <a href="configuracion.php" class="nav-link active block px-6 py-4 flex items-center gap-3"><span>⚙️</span> AJUSTES</a>
    </nav>
    <div class="p-6 border-t border-[#4e342e]">
        <a href="../../backend/auth/logout" class="block w-full bg-red-900/30 text-red-500 text-center py-2 rounded text-xs font-bold">LOGOUT</a>
    </div>
</aside>

<main class="flex-1 overflow-y-auto p-8 bg-[#0a0a0a]">
    <header class="mb-10">
        <h1 class="text-4xl font-bold text-amber-500 uppercase">Ajustes del Sistema</h1>
        <p class="text-zinc-400 text-sm">Modula los parámetros generales de la transmisión.</p>
    </header>

    <form id="configForm" class="max-w-4xl space-y-8">
        <!-- SECCIÓN GENERAL -->
        <div class="admin-card p-6 rounded-lg">
            <h3 class="text-amber-500 font-bold mb-6 border-b border-[#4e342e] pb-2 uppercase text-sm tracking-widest">Información General</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label>Nombre del Sitio / Artista</label>
                    <input type="text" name="nombre_sitio" id="nombre_sitio" placeholder="Jose Luis Arriola">
                </div>
                <div>
                    <label>ID de Video Principal (YouTube)</label>
                    <input type="text" name="youtube_id" id="youtube_id" placeholder="ID de 11 caracteres">
                    <span class="text-[9px] text-zinc-600 mt-1 block">Ej: LNGkfFc0Fpk</span>
                </div>
            </div>
        </div>

        <!-- SECCIÓN REDES SOCIALES -->
        <div class="admin-card p-6 rounded-lg">
            <h3 class="text-amber-500 font-bold mb-6 border-b border-[#4e342e] pb-2 uppercase text-sm tracking-widest">Redes Sociales (Enlaces)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label>Instagram URL</label>
                    <input type="url" name="instagram_url" id="instagram_url">
                </div>
                <div>
                    <label>YouTube Channel URL</label>
                    <input type="url" name="youtube_url" id="youtube_url">
                </div>
                <div>
                    <label>Facebook URL</label>
                    <input type="url" name="facebook_url" id="facebook_url">
                </div>
                <div>
                    <label>Spotify Artist URL</label>
                    <input type="url" name="spotify_url" id="spotify_url">
                </div>
            </div>
            <div class="mt-6">
                <label>Linktree / Bio Link URL</label>
                <input type="url" name="linktree_url" id="linktree_url">
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" id="btnSave" class="bg-amber-600 hover:bg-amber-500 text-black font-bold py-4 px-10 rounded uppercase tracking-widest text-xs transition-all shadow-lg">
                Guardar Cambios
            </button>
        </div>
    </form>
</main>

<script>
    const form = document.getElementById('configForm');
    const btn = document.getElementById('btnSave');

    // Cargar datos actuales
    async function loadConfig() {
        try {
            const response = await fetch('../../backend/configuracion/listar');
            const result = await response.json();

            if (result.status === 'success') {
                const data = result.data;
                // Llenamos el formulario dinámicamente si los IDs coinciden con las claves
                for (const clave in data) {
                    const input = document.getElementById(clave);
                    if (input) input.value = data[clave];
                }
            }
        } catch (error) {
            console.error("Error al sintonizar ajustes:", error);
        }
    }

    form.onsubmit = async (e) => {
        e.preventDefault();
        btn.disabled = true;
        btn.innerText = 'Guardando...';

        const formData = new FormData(form);
        try {
            const response = await fetch('../../backend/configuracion/guardar', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                alert("¡Señal actualizada! Los ajustes se han guardado.");
            } else {
                alert("Error: " + result.message);
            }
        } catch (error) {
            alert("Error de conexión con el backend.");
        } finally {
            btn.disabled = false;
            btn.innerText = 'Guardar Cambios';
        }
    };

    loadConfig();
</script>
</body>
</html>