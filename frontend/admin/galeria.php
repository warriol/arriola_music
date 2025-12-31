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

        .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; }
        .polaroid-item { background: #fff; padding: 10px 10px 30px 10px; color: #333; box-shadow: 0 10px 20px rgba(0,0,0,0.5); position: relative; }
        .polaroid-item img { width: 100%; height: 180px; object-fit: cover; border: 1px solid #eee; }

        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(5px); }
        .modal.active { display: flex; }
        .modal-content { background: #1a0f08; border: 4px solid #4e342e; width: 90%; max-width: 500px; padding: 2rem; }

        input, textarea, select { background: #000 !important; border: 1px solid #4e342e !important; color: #fdf2d9 !important; padding: 10px; width: 100%; margin-top: 5px; }
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
    </nav>
    <div class="p-6 border-t border-[#4e342e]">
        <a href="../../backend/auth/logout" class="block w-full bg-red-900/30 text-red-500 text-center py-2 rounded text-xs font-bold">LOGOUT</a>
    </div>
</aside>

<main class="flex-1 overflow-y-auto p-8 bg-[#0a0a0a]">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-4xl font-bold text-amber-500 uppercase">Archivo Fotográfico</h1>
            <p class="text-zinc-400 text-sm">Capturas de la frecuencia visual.</p>
        </div>
        <button onclick="openModal()" class="bg-amber-600 hover:bg-amber-500 text-black font-bold py-3 px-6 rounded uppercase tracking-widest text-xs transition-all">
            + Subir Foto
        </button>
    </header>

    <div id="galleryContainer" class="photo-grid">
        <!-- Cargado vía JS -->
        <p class="text-zinc-600 italic">Sintonizando imágenes...</p>
    </div>
</main>

<!-- MODAL SUBIDA -->
<div id="galeriaModal" class="modal">
    <div class="modal-content">
        <h3 class="text-xl font-bold text-amber-500 mb-6 uppercase">Nueva Captura</h3>
        <form id="galeriaForm" class="space-y-4">
            <div>
                <label class="block text-[10px] uppercase font-bold text-zinc-500">Archivo de Imagen</label>
                <input type="file" name="imagen" id="f_imagen" accept="image/*" required>
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold text-zinc-500">Pie de Foto (Etiqueta)</label>
                <input type="text" name="pie_de_foto" id="f_pie" placeholder="Ej: Grabación en Estudios Ion" required>
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold text-zinc-500">Estado</label>
                <select name="visible" id="f_visible">
                    <option value="1">Visible</option>
                    <option value="0">Oculto</option>
                </select>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 border border-zinc-700 py-3 font-bold uppercase text-xs hover:bg-zinc-800">Cancelar</button>
                <button type="submit" id="btnSubmit" class="flex-1 bg-amber-600 text-black py-3 font-bold uppercase text-xs hover:bg-amber-500">Subir</button>
            </div>
        </form>
    </div>
</div>

<script>
    const container = document.getElementById('galleryContainer');
    const modal = document.getElementById('galeriaModal');
    const form = document.getElementById('galeriaForm');

    async function loadGallery() {
        try {
            const response = await fetch('../../backend/galeria/listar?admin=true');
            const result = await response.json();
            if (result.status === 'success') renderGallery(result.data);
        } catch (error) {
            container.innerHTML = '<p class="text-red-500">Error al conectar con la estación.</p>';
        }
    }

    function renderGallery(data) {
        if (data.length === 0) {
            container.innerHTML = '<p class="text-zinc-600">No hay fotos registradas.</p>';
            return;
        }

        container.innerHTML = data.map(item => `
                <div class="polaroid-item group">
                    <img src="../../media/galeria/${item.url_imagen}" alt="${item.pie_de_foto}">
                    <p class="text-[11px] font-bold text-center mt-2 uppercase tracking-tighter truncate">${item.pie_de_foto}</p>
                    <div class="absolute inset-0 bg-black/80 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity p-4">
                        <span class="text-[9px] text-amber-500 font-bold mb-4">${item.url_imagen}</span>
                        <button onclick="deletePhoto(${item.id})" class="bg-red-600 text-white px-4 py-2 text-[10px] font-bold uppercase">Eliminar</button>
                    </div>
                </div>
            `).join('');
    }

    function openModal() { modal.classList.add('active'); }
    function closeModal() { modal.classList.remove('active'); form.reset(); }

    form.onsubmit = async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerText = 'Subiendo...';

        const formData = new FormData(form);
        try {
            const response = await fetch('../../backend/galeria/guardar', { method: 'POST', body: formData });
            const result = await response.json();
            if (result.status === 'success') {
                closeModal();
                loadGallery();
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert("Error de transmisión.");
        } finally {
            btn.disabled = false;
            btn.innerText = 'Subir';
        }
    };

    async function deletePhoto(id) {
        if (!confirm('¿Borrar esta captura del archivo?')) return;
        try {
            const response = await fetch(`../../backend/galeria/borrar/${id}`);
            const result = await response.json();
            if (result.status === 'success') loadGallery();
        } catch (error) {
            console.error(error);
        }
    }

    loadGallery();
</script>
</body>
</html>