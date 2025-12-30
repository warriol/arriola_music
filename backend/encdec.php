<?php
// Función para encriptar el texto
function encriptarTexto($texto, $clave)
{
    // Generar un IV (vector de inicialización) aleatorio
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

    // Encriptar el texto
    $textoEncriptado = openssl_encrypt($texto, 'aes-256-cbc', $clave, 0, $iv);

    // Codificar el IV y el texto encriptado en base64 para que se puedan almacenar y mostrar
    return base64_encode($iv . $textoEncriptado);
}

function desencriptarTexto($textoEncriptado, $clave)
{
    // Decodificar el texto encriptado y el IV en base64
    $datos = base64_decode($textoEncriptado);

    // Extraer el IV del principio del texto
    $iv = substr($datos, 0, openssl_cipher_iv_length('aes-256-cbc'));

    // Extraer el texto encriptado después del IV
    $textoEncriptado = substr($datos, openssl_cipher_iv_length('aes-256-cbc'));

    // Desencriptar el texto
    return openssl_decrypt($textoEncriptado, 'aes-256-cbc', $clave, 0, $iv);
}

// Inicializar variables
$textoEncriptado = "";
$clave = 'josearriola'; // La clave debe ser segura y no predecible

// Verificar si el formulario ha sido enviado para encriptar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['texto'])) {
    $texto = $_POST['texto'];
    // Llamar a la función de encriptación
    $textoEncriptado = encriptarTexto($texto, $clave);
    $textoNormal = desencriptarTexto($textoEncriptado, $clave);
}

// Verificar si el formulario ha sido enviado para desencriptar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['texto_encriptado'])) {
    $textoEncriptado = $_POST['texto_encriptado'];
    // Llamar a la función de desencriptación
    $textoNormal = desencriptarTexto($textoEncriptado, $clave);
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Encriptación</title>
</head>

<body>
    <h2>Formulario de Encriptación de Texto</h2>

    <!-- Formulario HTML para encriptar -->
    <form method="POST" action="">
        <label for="texto">Ingresa el texto a encriptar:</label><br>
        <input type="text" id="texto" name="texto" required><br><br>
        <button type="submit">Encriptar</button>
    </form>

    <!-- Formulario HTML para desencriptar -->
    <form method="post" action="">
        <label for="texto_encriptado">Ingresa el texto encriptado a desencriptar:</label><br>
        <input type="text" id="texto_encriptado" name="texto_encriptado" required><br><br>
        <button type="submit">Desencriptar</button>
    </form>

    <?php if ($textoEncriptado): ?>
        <h3>Texto encriptado:</h3>
        <p><?php echo htmlspecialchars($textoEncriptado); ?></p>
        <h3>Texto desencriptado:</h3>
        <p><?php echo htmlspecialchars($textoNormal); ?></p>
    <?php endif; ?>
</body>

</html>