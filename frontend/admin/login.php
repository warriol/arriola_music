<!DOCTYPE html>
<html lang="es">
<head>
    <?php
    include_once '../templates/meta.php';
    ?>
    <title>Login - Panel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../../media/img/favicon.png" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        body { font-family: 'Courier Prime', monospace; background: #0f0f0f; color: #fdf2d9; }
        .login-box { background: #1a0f08; border: 4px solid #4e342e; box-shadow: 0 0 20px rgba(0,0,0,0.8); }
    </style>
</head>
<body class="h-screen flex items-center justify-center">
<div class="login-box p-8 rounded-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-amber-500 mb-6 text-center tracking-tighter uppercase">Sintonía Admin</h2>

    <form id="loginForm" class="space-y-4">
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Usuario</label>
            <input type="text" name="username" id="username" class="w-full bg-black border border-[#4e342e] p-2 text-amber-200 outline-none focus:border-amber-500" required>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase mb-1">Contraseña</label>
            <input type="password" id="password_raw" class="w-full bg-black border border-[#4e342e] p-2 text-amber-200 outline-none focus:border-amber-500" required>
        </div>
        <button type="submit" class="w-full bg-amber-700 hover:bg-amber-600 p-3 font-bold uppercase tracking-widest text-black transition-colors">Entrar</button>
    </form>
    <p id="msg" class="mt-4 text-center text-red-500 text-sm hidden"></p>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = document.getElementById('msg');
        const username = document.getElementById('username').value;
        const passwordRaw = document.getElementById('password_raw').value;

        const passwordHash = CryptoJS.SHA512(passwordRaw).toString();

        const formData = new FormData();
        formData.append('username', username);
        formData.append('password', passwordHash);

        try {
            const response = await fetch('../../backend/auth/login', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.status === 'success') {
                window.location.href = result.redirect;
            } else {
                msg.textContent = result.message;
                msg.classList.remove('hidden');
            }
        } catch (error) {
            msg.textContent = "Error de sintonía con el servidor.";
            msg.classList.remove('hidden');
        }
    });
</script>
</body>
</html>