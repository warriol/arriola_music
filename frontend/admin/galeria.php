<?php
/**
 * ARCHIVO: frontend/admin/galeria.php
 * Descripción: Panel para gestionar las fotos Polaroid.
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
    <title>Gestión Galería - Sintonía Artística</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../../media/img/favicon.png" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        body { font-family: 'Courier Prime', monospace; background: #0f0f0f; color: #fdf2d9; }
        .admin-card { background: #1a0f08; border: 2px solid #4e342e; }
        .sidebar { background: linear-gradient(180deg, #2d1b0e 0%, #1a0f08 100%); border-right: 4px solid #111; }
        .nav-link { transition: all 0.3s ease; border-left: 4px solid transparent; }
        .nav-link:hover, .nav-link.active { background: #4e342e; border-left-color: #ff1a1a; color: #ffb347; }
        .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.5rem; }
        .polaroid-item { background: #fff; padding: 8px 8px 25px 8px; color: #333; box-shadow: 0 10px 20px rgba(0,0,0,0.5); position: relative; }
        .polaroid-item img { width: 100%; height: 150px; object-fit: cover; border: 1px solid #eee; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(5px); }
        .modal.active { display: flex; }
        input[type="file"]::file-selector-button { background: #4e342e; color: #ffb347; border: none; padding: 5px 15px; cursor: pointer; font-weight: bold; }
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
        <a href="galeria.php" class="nav-link active block px-6 py-4 flex items-center gap-3"><span>📸</span> GALERÍA</a>
        <a href="configuracion.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>⚙️</span> AJUSTES</a>
        <a href="usuarios.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>👥</span> USUARIOS</a>
    </nav>
</aside>

<main class="flex-1 overflow-y-auto p-8 bg-[#0a0a0a]">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-4xl font-bold text-amber-500 uppercase">Multimedia</h1>
            <p class="text-zinc-400 text-sm">Sube múltiples archivos de Google Drive o tu PC.</p>
        </div>
        <button onclick="openModal()" class="bg-amber-600 hover:bg-amber-500 text-black font-bold py-3 px-6 rounded uppercase tracking-widest text-xs">
            + Subida Masiva
        </button>
    </header>

    <div id="galleryContainer" class="photo-grid">
        <!-- Dinámico -->
    </div>
</main>

<!-- MODAL MULTIPLE -->
<div id="galeriaModal" class="modal">
    <div class="modal-content bg-[#1a0f08] border-4 border-[#4e342e] p-8 w-full max-w-lg">
        <h3 class="text-xl font-bold text-amber-500 mb-6 uppercase">Sintonización Masiva</h3>
        <form id="galeriaForm" class="space-y-4">
            <div>
                <label class="block text-[10px] uppercase font-bold text-zinc-500">Seleccionar Imágenes (puedes elegir muchas)</label>
                <input type="file" name="imagenes[]" id="f_imagenes" accept="image/*" multiple required class="w-full mt-1">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold text-zinc-500">Etiqueta General (Opcional)</label>
                <input type="text" name="pie_de_foto" id="f_pie" placeholder="Si dejas vacío, usará el nombre del archivo" class="w-full bg-black border border-[#4e342e] p-2 text-amber-200">
            </div>
            <div id="uploadStatus" class="hidden text-[10px] text-amber-500 italic animate-pulse">
                Procesando archivos en la frecuencia... por favor espera.
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal()" id="btnCancel" class="flex-1 border border-zinc-700 py-3 font-bold uppercase text-xs hover:bg-zinc-800">Cerrar</button>
                <button type="submit" id="btnSubmit" class="flex-1 bg-amber-600 text-black py-3 font-bold uppercase text-xs hover:bg-amber-500">Iniciar Subida</button>
            </div>
        </form>
    </div>
</div>

<script>
    const container = document.getElementById('galleryContainer');
    const modal = document.getElementById('galeriaModal');
    const form = document.getElementById('galeriaForm');

    async function loadGallery() {
        const response = await fetch('../../backend/galeria/listar?admin=true');
        const result = await response.json();
        if (result.status === 'success') renderGallery(result.data);
    }

    function renderGallery(data) {
        if (data.length === 0) { container.innerHTML = '<p class="text-zinc-600">Vacío.</p>'; return; }
        container.innerHTML = data.map(item => `
                <div class="polaroid-item group">
                    <img src="../../media/galeria/${item.url_imagen}" alt="${item.pie_de_foto}">
                    <p class="text-[9px] font-bold text-center mt-2 uppercase truncate px-1">${item.pie_de_foto}</p>
                    <div class="absolute inset-0 bg-black/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="deletePhoto(${item.id})" class="bg-red-600 text-white px-3 py-1 text-[9px] font-bold uppercase">Borrar</button>
                    </div>
                </div>
            `).join('');
    }

    function openModal() { modal.classList.add('active'); }
    function closeModal() { modal.classList.remove('active'); form.reset(); document.getElementById('uploadStatus').classList.add('hidden'); }

    form.onsubmit = async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btnSubmit');
        const status = document.getElementById('uploadStatus');

        btn.disabled = true;
        status.classList.remove('hidden');

        const formData = new FormData(form);
        try {
            const response = await fetch('../../backend/galeria/guardar', { method: 'POST', body: formData });
            const result = await response.json();
            alert(result.message);
            if (result.status === 'success') { closeModal(); loadGallery(); }
        } catch (error) {
            alert("Error de conexión.");
        } finally {
            btn.disabled = false;
        }
    };

    async function deletePhoto(id) {
        if (confirm('¿Eliminar?')) {
            await fetch(`../../backend/galeria/borrar/${id}`);
            loadGallery();
        }
    }

    loadGallery();
</script>
</body>
</html>