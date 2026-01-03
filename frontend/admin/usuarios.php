<?php
/**
 * ARCHIVO: frontend/admin/usuarios.php
 * Descripción: Panel para administrar los usuarios con acceso al sistema.
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
    <title>Gestión Usuarios - Sintonía Artística</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../../media/img/favicon.png" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        body { font-family: 'Courier Prime', monospace; background: #0f0f0f; color: #fdf2d9; }
        .admin-card { background: #1a0f08; border: 2px solid #4e342e; }
        .sidebar { background: linear-gradient(180deg, #2d1b0e 0%, #1a0f08 100%); border-right: 4px solid #111; }
        .nav-link { transition: all 0.3s ease; border-left: 4px solid transparent; }
        .nav-link:hover, .nav-link.active { background: #4e342e; border-left-color: #ff1a1a; color: #ffb347; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(5px); }
        .modal.active { display: flex; }
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
        <a href="configuracion.php" class="nav-link block px-6 py-4 flex items-center gap-3"><span>⚙️</span> AJUSTES</a>
        <a href="usuarios.php" class="nav-link active block px-6 py-4 flex items-center gap-3"><span>👥</span> USUARIOS</a>
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
            <h1 class="text-4xl font-bold text-amber-500 uppercase">Operadores</h1>
            <p class="text-zinc-400 text-sm">Administración de frecuencias de acceso.</p>
        </div>
        <button onclick="openModal()" class="bg-amber-600 hover:bg-amber-500 text-black font-bold py-3 px-6 rounded uppercase tracking-widest text-xs transition-all">
            + Nuevo Operador
        </button>
    </header>

    <div class="admin-card rounded-lg overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-black text-amber-500 uppercase text-[10px] tracking-widest">
            <tr>
                <th class="p-4">Usuario</th>
                <th class="p-4">Email</th>
                <th class="p-4 text-right">Acciones</th>
            </tr>
            </thead>
            <tbody id="userList">
            </tbody>
        </table>
    </div>
</main>

<div id="userModal" class="modal">
    <div class="modal-content bg-[#1a0f08] border-4 border-[#4e342e] p-8 w-full max-w-md">
        <h3 class="text-xl font-bold text-amber-500 mb-6 uppercase">Nuevo Operador</h3>
        <form id="userForm" class="space-y-4">
            <input type="text" id="u_username" placeholder="Nombre de Usuario" class="w-full bg-black border border-[#4e342e] p-3 text-amber-200 outline-none" required>
            <input type="email" id="u_email" placeholder="Correo Electrónico" class="w-full bg-black border border-[#4e342e] p-3 text-amber-200 outline-none">
            <input type="password" id="u_pass" placeholder="Contraseña" class="w-full bg-black border border-[#4e342e] p-3 text-amber-200 outline-none" required>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 border border-zinc-700 py-3 font-bold uppercase text-xs hover:bg-zinc-800">Cerrar</button>
                <button type="submit" class="flex-1 bg-amber-600 text-black py-3 font-bold uppercase text-xs hover:bg-amber-500">Sintonizar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const userList = document.getElementById('userList');
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');

    async function loadUsers() {
        try {
            const response = await fetch('../../backend/usuario/listar');
            const result = await response.json();
            if (result.status === 'success') renderUsers(result.data);
        } catch (error) {
            userList.innerHTML = '<tr><td colspan="3" class="p-10 text-center text-red-500 italic">Error de señal...</td></tr>';
        }
    }

    function renderUsers(data) {
        userList.innerHTML = data.map(user => `
                <tr class="border-t border-[#4e342e] hover:bg-white/5 transition-colors">
                    <td class="p-4 font-bold text-amber-200">${user.username} ${user.username === '<?php echo $username; ?>' ? '<span class="text-[8px] bg-amber-900 text-amber-300 px-1 rounded ml-2 uppercase">Mando Actual</span>' : ''}</td>
                    <td class="p-4 text-zinc-400">${user.email || '---'}</td>
                    <td class="p-4 flex justify-end gap-5">
                        <button onclick="resetPassword('${user.username}')" title="Resetear Contraseña" class="text-amber-500 hover:text-amber-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3m-3-3l-2.5-2.5"/></svg>
                        </button>
                        ${user.username !== 'admin' && user.username !== '<?php echo $username; ?>' ? `
                            <button onclick="deleteUser('${user.username}')" title="Eliminar" class="text-red-500 hover:text-red-400 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </button>
                        ` : ''}
                    </td>
                </tr>
            `).join('');
    }

    function openModal() { modal.classList.add('active'); }
    function closeModal() { modal.classList.remove('active'); form.reset(); }

    form.onsubmit = async (e) => {
        e.preventDefault();
        const passHash = CryptoJS.SHA512(document.getElementById('u_pass').value).toString();
        const formData = new FormData();
        formData.append('username', document.getElementById('u_username').value);
        formData.append('email', document.getElementById('u_email').value);
        formData.append('password', passHash);

        const res = await fetch('../../backend/usuario/guardar', { method: 'POST', body: formData });
        const result = await res.json();
        if (result.status === 'success') { closeModal(); loadUsers(); } else alert(result.message);
    };

    async function resetPassword(username) {
        const newPass = prompt(`Introduce la nueva contraseña para ${username}:`);
        if (!newPass) return;
        const passHash = CryptoJS.SHA512(newPass).toString();
        const formData = new FormData();
        formData.append('username', username);
        formData.append('password', passHash);

        const res = await fetch('../../backend/usuario/resetear', { method: 'POST', body: formData });
        const result = await res.json();
        alert(result.message);
    }

    async function deleteUser(username) {
        if (confirm(`¿Apagar frecuencia del usuario ${username}?`)) {
            const res = await fetch(`../../backend/usuario/borrar/${username}`);
            const result = await res.json();
            if (result.status === 'success') loadUsers();
        }
    }

    loadUsers();
</script>
</body>
</html>