<?php
/**
 * ARCHIVO: frontend/admin/tour.php
 * Descripción: Panel de gestión para las fechas del Tour.
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
    <title>Gestión Tour - Sintonía Artística</title>
    <link rel="icon" href="../../media/img/favicon.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        body { font-family: 'Courier Prime', monospace; background: #0f0f0f; color: #fdf2d9; }
        .admin-card { background: #1a0f08; border: 2px solid #4e342e; }
        .sidebar { background: linear-gradient(180deg, #2d1b0e 0%, #1a0f08 100%); border-right: 4px solid #111; }
        .nav-link { transition: all 0.3s ease; border-left: 4px solid transparent; }
        .nav-link:hover, .nav-link.active { background: #4e342e; border-left-color: #ff1a1a; color: #ffb347; }

        /* Estilo de Tabla */
        .tour-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        .tour-table tr { background: #1a0f08; transition: transform 0.2s; }
        .tour-table tr:hover { transform: scale(1.01); background: #26170d; }
        .tour-table td, .tour-table th { padding: 16px; border-top: 1px solid #4e342e; border-bottom: 1px solid #4e342e; }
        .tour-table th { background: #000; color: #ffb347; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 2px; text-align: left; }

        /* Modal */
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(5px); }
        .modal.active { display: flex; }
        .modal-content { background: #1a0f08; border: 4px solid #4e342e; width: 90%; max-width: 600px; padding: 2rem; position: relative; }

        input, textarea, select { background: #000 !important; border: 1px solid #4e342e !important; color: #fdf2d9 !important; padding: 10px; outline: none; width: 100%; }
        input:focus { border-color: #ffb347 !important; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

<!-- SIDEBAR (Igual al Dashboard) -->
<aside class="sidebar w-64 flex-shrink-0 flex flex-col">
    <div class="p-6 border-b border-[#4e342e]">
        <h2 class="text-xl font-bold text-amber-500 uppercase">Sintonía Admin</h2>
    </div>
    <nav class="flex-1 mt-4">
        <a href="dashboard.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>📻</span> INICIO</a>
        <a href="tour.php" class="nav-link active block px-6 py-4 flex items-center gap-3"><span>🗓️</span> GESTIÓN TOUR</a>
        <a href="galeria.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>📸</span> GALERÍA</a>
    </nav>
    <div class="p-6 border-t border-[#4e342e]">
        <a href="../../backend/auth/logout" class="block w-full bg-red-900/30 text-red-500 text-center py-2 rounded text-xs font-bold">LOGOUT</a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="flex-1 overflow-y-auto p-8 bg-[#0a0a0a]">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-4xl font-bold text-amber-500 uppercase">Gestión de Tour</h1>
            <p class="text-zinc-400 text-sm">Sintoniza las próximas fechas en el dial.</p>
        </div>
        <button onclick="openModal()" class="bg-amber-600 hover:bg-amber-500 text-black font-bold py-3 px-6 rounded uppercase tracking-widest text-xs transition-all">
            + Nueva Fecha
        </button>
    </header>

    <!-- LISTADO DE FECHAS -->
    <div class="overflow-x-auto">
        <table class="tour-table" id="tourTable">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Lugar / Evento</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="tourList">
            <!-- Cargado vía JS -->
            <tr><td colspan="4" class="text-center py-10 text-zinc-600 italic">Sintonizando señal...</td></tr>
            </tbody>
        </table>
    </div>
</main>

<!-- MODAL DE EDICIÓN / CREACIÓN -->
<div id="tourModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle" class="text-xl font-bold text-amber-500 mb-6 uppercase">Nueva Fecha de Tour</h3>
        <form id="tourForm" class="space-y-4">
            <input type="hidden" name="id" id="tourId">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] uppercase font-bold mb-1 text-zinc-500">Fecha</label>
                    <input type="date" name="fecha" id="f_fecha" required>
                </div>
                <div>
                    <label class="block text-[10px] uppercase font-bold mb-1 text-zinc-500">Lugar</label>
                    <input type="text" name="lugar" id="f_lugar" placeholder="Teatro / Ciudad" required>
                </div>
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold mb-1 text-zinc-500">Descripción / Título del Show</label>
                <input type="text" name="descripcion" id="f_descripcion" placeholder="Ej: La última mudanza">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold mb-1 text-zinc-500">Dirección</label>
                <input type="text" name="direccion" id="f_direccion" placeholder="Calle 123, Ciudad">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] uppercase font-bold mb-1 text-zinc-500">URL Tickets</label>
                    <input type="url" name="url_tickets" id="f_url_tickets" placeholder="https://...">
                </div>
                <div>
                    <label class="block text-[10px] uppercase font-bold mb-1 text-zinc-500">Hashtag</label>
                    <input type="text" name="hashtag" id="f_hashtag" placeholder="SintoníaDigital">
                </div>
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold mb-1 text-zinc-500">Visibilidad</label>
                <select name="visible" id="f_visible">
                    <option value="1">Sintonizado (Visible)</option>
                    <option value="0">Fuera de Aire (Oculto)</option>
                </select>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 border border-zinc-700 py-3 font-bold uppercase text-xs hover:bg-zinc-800">Cancelar</button>
                <button type="submit" class="flex-1 bg-amber-600 text-black py-3 font-bold uppercase text-xs hover:bg-amber-500">Guardar Frecuencia</button>
            </div>
        </form>
    </div>
</div>

<script>
    const tourList = document.getElementById('tourList');
    const tourModal = document.getElementById('tourModal');
    const tourForm = document.getElementById('tourForm');

    async function loadTours() {
        try {
            // Llamamos al controlador con modo admin para ver todo
            const response = await fetch('../../backend/tour/listar?admin=true');
            const result = await response.json();

            if (result.status === 'success') {
                renderTours(result.data);
            }
        } catch (error) {
            tourList.innerHTML = '<tr><td colspan="4" class="text-center text-red-500">Error de sintonía con el servidor.</td></tr>';
        }
    }

    function renderTours(data) {
        if (data.length === 0) {
            tourList.innerHTML = '<tr><td colspan="4" class="text-center py-10 text-zinc-600 italic">No hay frecuencias grabadas.</td></tr>';
            return;
        }

        tourList.innerHTML = data.map(item => `
                <tr>
                    <td class="font-bold text-amber-200">${item.fecha}</td>
                    <td>
                        <div class="font-bold">${item.lugar}</div>
                        <div class="text-[10px] text-zinc-500 uppercase">${item.descripcion || ''}</div>
                    </td>
                    <td>
                        <span class="px-2 py-1 text-[9px] font-bold rounded ${item.visible == 1 ? 'bg-green-900/30 text-green-500' : 'bg-zinc-800 text-zinc-500'}">
                            ${item.visible == 1 ? 'ONLINE' : 'OFFLINE'}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <button onclick='editTour(${JSON.stringify(item)})' class="text-blue-400 hover:text-blue-300 text-xs uppercase font-bold">Editar</button>
                            <button onclick="deleteTour(${item.id})" class="text-red-500 hover:text-red-400 text-xs uppercase font-bold">Borrar</button>
                        </div>
                    </td>
                </tr>
            `).join('');
    }

    function openModal(data = null) {
        tourForm.reset();
        document.getElementById('tourId').value = '';
        document.getElementById('modalTitle').innerText = data ? 'Editar Frecuencia' : 'Nueva Fecha de Tour';

        if (data) {
            document.getElementById('tourId').value = data.id;
            document.getElementById('f_fecha').value = data.fecha;
            document.getElementById('f_lugar').value = data.lugar;
            document.getElementById('f_descripcion').value = data.descripcion;
            document.getElementById('f_direccion').value = data.direccion;
            document.getElementById('f_url_tickets').value = data.url_tickets;
            document.getElementById('f_hashtag').value = data.hashtag;
            document.getElementById('f_visible').value = data.visible;
        }

        tourModal.classList.add('active');
    }

    function closeModal() {
        tourModal.classList.remove('active');
    }

    function editTour(item) {
        openModal(item);
    }

    // Guardar o Actualizar
    tourForm.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(tourForm);
        const id = document.getElementById('tourId').value;

        // Ruta dinámica según si editamos o creamos
        const url = id ? `../../backend/tour/actualizar/${id}` : '../../backend/tour/guardar';

        try {
            const response = await fetch(url, { method: 'POST', body: formData });
            const result = await response.json();

            if (result.success || result.status === 'success') {
                closeModal();
                loadTours();
            } else {
                alert("Error al modular la señal: " + (result.message || 'Desconocido'));
            }
        } catch (error) {
            console.error(error);
        }
    };

    // Borrar Fecha
    async function deleteTour(id) {
        // Nota: En un entorno real, usa un modal personalizado en vez de confirm()
        if (!confirm('¿Seguro que quieres apagar esta frecuencia permanentemente?')) return;

        try {
            const response = await fetch(`../../backend/tour/borrar/${id}`);
            const result = await response.json();
            if (result.success || result.status === 'success') loadTours();
        } catch (error) {
            console.error(error);
        }
    }

    // Inicio
    loadTours();
</script>
</body>
</html>