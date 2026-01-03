<?php
/**
 * ARCHIVO: frontend/admin/galeria.php
 * Descripción: Panel para gestionar las fotos Polaroid.
 */

require_once '../../backend/autoload.php';

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
    <?php
    include_once '../templates/meta.php';
    ?>
    <title>Gestión Galería - Sintonía Artística</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../../media/img/favicon.png" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        body { font-family: 'Courier Prime', monospace; background: #0f0f0f; color: #fdf2d9; }
        .admin-card { background: #1a0f08; border: 2px solid #4e342e; transition: all 0.3s; }
        .admin-card:hover { border-color: #ffb347; transform: translateY(-4px); }
        .sidebar { background: linear-gradient(180deg, #2d1b0e 0%, #1a0f08 100%); border-right: 4px solid #111; }
        .nav-link { transition: all 0.3s ease; border-left: 4px solid transparent; }
        .nav-link:hover, .nav-link.active { background: #4e342e; border-left-color: #ff1a1a; color: #ffb347; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
        .modal.active { display: flex; }
        .modal-content { background: #1a0f08; border: 4px solid #4e342e; width: 95%; max-width: 900px; max-height: 90vh; overflow-y: auto; padding: 2rem; position: relative; }
        input, select, textarea { background: #000 !important; border: 1px solid #4e342e !important; color: #fdf2d9 !important; padding: 10px; width: 100%; outline: none; }
        input:focus, select:focus { border-color: #ffb347 !important; }
        .album-cover { height: 160px; background: #000; display: flex; items-center; justify-content: center; border-bottom: 2px solid #4e342e; }
        .photo-item { position: relative; background: #fff; padding: 8px 8px 30px 8px; box-shadow: 0 10px 20px rgba(0,0,0,0.5); transform: rotate(var(--rot, 0deg)); transition: z-index 0.3s; }
        .photo-item:hover { z-index: 50; }
        .photo-item img { width: 100%; height: 150px; object-fit: cover; }
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

<main class="flex-1 overflow-y-auto p-8 bg-[#0a0a0a]">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-4xl font-bold text-amber-500 uppercase">Multimedia</h1>
            <p class="text-zinc-400 text-sm">Gestiona álbumes y frecuencias visuales del artista.</p>
        </div>
        <div class="flex gap-4">
            <button onclick="openModal('albumModal')" class="border border-amber-600 text-amber-500 hover:bg-amber-600 hover:text-black font-bold py-3 px-6 rounded uppercase tracking-widest text-xs transition-all">
                + Nuevo Álbum
            </button>
            <button onclick="openModal('uploadModal')" class="bg-amber-600 hover:bg-amber-500 text-black font-bold py-3 px-6 rounded uppercase tracking-widest text-xs transition-all">
                + Subir Fotos
            </button>
        </div>
    </header>

    <div id="albumGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Dinámico -->
    </div>
</main>

<!-- MODAL: NUEVO ÁLBUM -->
<div id="albumModal" class="modal">
    <div class="modal-content !max-w-md">
        <h3 class="text-xl font-bold text-amber-500 mb-6 uppercase">Sintonizar Nuevo Álbum</h3>
        <form id="albumForm" class="space-y-4">
            <input type="text" name="nombre" placeholder="Nombre del Álbum (ej: Concierto 2025)" required>
            <textarea name="descripcion" placeholder="Breve descripción..." rows="3"></textarea>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal('albumModal')" class="flex-1 border border-zinc-700 py-3 font-bold uppercase text-xs hover:bg-zinc-800">Cancelar</button>
                <button type="submit" class="flex-1 bg-amber-600 text-black py-3 font-bold uppercase text-xs hover:bg-amber-500">Crear Álbum</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: SUBIDA DE FOTOS -->
<div id="uploadModal" class="modal">
    <div class="modal-content !max-w-md">
        <h3 class="text-xl font-bold text-amber-500 mb-6 uppercase">Subida Masiva</h3>
        <form id="uploadForm" class="space-y-4">
            <select name="album_id" id="f_album_id" required></select>
            <input type="file" name="imagenes[]" multiple accept="image/*" required class="bg-black">
            <input type="text" name="pie_de_foto" placeholder="Etiqueta general (opcional)">
            <div id="uploadStatus" class="hidden text-center text-amber-500 animate-pulse text-[10px] uppercase font-bold">Procesando señal...</div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal('uploadModal')" class="flex-1 border border-zinc-700 py-3 font-bold uppercase text-xs hover:bg-zinc-800">Cerrar</button>
                <button type="submit" id="btnUpload" class="flex-1 bg-amber-600 text-black py-3 font-bold uppercase text-xs hover:bg-amber-500">Iniciar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: VISOR DE FOTOS -->
<div id="albumViewModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-6 border-b border-[#4e342e] pb-4">
            <h3 id="viewAlbumTitle" class="text-2xl font-bold text-amber-500 uppercase">Álbum</h3>
            <button onclick="closeModal('albumViewModal')" class="text-zinc-400 hover:text-white text-3xl">&times;</button>
        </div>
        <div id="photosGrid" class="grid grid-cols-2 md:grid-cols-4 gap-6"></div>
    </div>
</div>

<script>
    const albumGrid = document.getElementById('albumGrid');
    const albumForm = document.getElementById('albumForm');
    const uploadForm = document.getElementById('uploadForm');

    async function loadAlbumes() {
        const res = await fetch('../../backend/galeria/listarAlbumes?admin=true');
        const result = await res.json();
        if (result.status === 'success') {
            renderAlbumes(result.data);
            document.getElementById('f_album_id').innerHTML = result.data.map(a => `<option value="${a.id}">${a.nombre}</option>`).join('');
        }
    }

    function renderAlbumes(data) {
        albumGrid.innerHTML = data.map(a => `
                <div class="admin-card rounded-lg overflow-hidden cursor-pointer" onclick="viewAlbum(${a.id}, '${a.nombre}')">
                    <div class="album-cover text-4xl">📁</div>
                    <div class="p-4"><h4 class="font-bold text-amber-500 uppercase text-xs">${a.nombre}</h4></div>
                </div>
            `).join('');
    }

    async function viewAlbum(id, nombre) {
        document.getElementById('viewAlbumTitle').innerText = nombre;
        document.getElementById('albumViewModal').classList.add('active');
        const res = await fetch(`../../backend/galeria/listarFotos?album_id=${id}&admin=true`);
        const result = await res.json();
        const grid = document.getElementById('photosGrid');
        grid.innerHTML = result.data.map(p => `
                <div class="photo-item group" style="--rot: ${(Math.random() * 4 - 2)}deg">
                    <img src="../../media/galeria/${p.url_imagen}" alt="">
                    <button onclick="deletePhoto(${p.id}, ${id}, '${nombre}')" class="absolute top-1 right-1 bg-red-600 text-white p-1 opacity-0 group-hover:opacity-100 transition-opacity">🗑️</button>
                </div>
            `).join('');
    }

    albumForm.onsubmit = async (e) => {
        e.preventDefault();
        const res = await fetch('../../backend/galeria/guardarAlbum', { method: 'POST', body: new FormData(albumForm) });
        const result = await res.json();
        if (result.status === 'success') { closeModal('albumModal'); loadAlbumes(); } else alert(result.message);
    };

    uploadForm.onsubmit = async (e) => {
        e.preventDefault();
        document.getElementById('btnUpload').disabled = true;
        document.getElementById('uploadStatus').classList.remove('hidden');
        const res = await fetch('../../backend/galeria/guardar', { method: 'POST', body: new FormData(uploadForm) });
        const result = await res.json();
        alert(result.message);
        closeModal('uploadModal'); loadAlbumes();
        document.getElementById('btnUpload').disabled = false;
        document.getElementById('uploadStatus').classList.add('hidden');
    };

    async function deletePhoto(id, albumId, nombre) {
        if (confirm('¿Eliminar permanente?')) {
            await fetch(`../../backend/galeria/borrar/${id}`);
            viewAlbum(albumId, nombre);
        }
    }

    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); document.querySelectorAll('form').forEach(f => f.reset()); }

    loadAlbumes();
</script>
</body>
</html></html>