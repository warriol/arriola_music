<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "banda_db");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar Formulario de Contacto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email = $conn->real_escape_string($_POST['email']);
    $mensaje = $conn->real_escape_string($_POST['mensaje']);
    
    $sql = "INSERT INTO contacto (nombre, email, mensaje) VALUES ('$nombre', '$email', '$mensaje')";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nombre de la Banda | Sitio Oficial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root {
        --bg-dark: #0a0a0a;
        --accent: #ff3e3e; /* Rojo vibrante tipo Creedence */
        --text-muted: #888;
    }

    body {
        background-color: var(--bg-dark);
        color: #fff;
        font-family: 'Inter', sans-serif; /* Tipografía moderna */
        overflow-x: hidden;
    }

    h1, h2, h3 {
        text-transform: uppercase;
        font-weight: 900;
        letter-spacing: -1px;
    }

    /* Navbar Minimalista */
    .navbar {
        padding: 20px 0;
        border-bottom: 1px solid #222;
    }

    /* Sección Quiénes Somos Asimétrica */
    .img-cantante {
        filter: grayscale(100%);
        transition: 0.5s;
        border-left: 5px solid var(--accent);
    }
    .img-cantante:hover { filter: grayscale(0%); }

    .integrante-thumb {
        width: 100px; height: 100px;
        object-fit: cover;
        filter: grayscale(100%);
        border: 2px solid #333;
        transition: 0.3s;
    }
    .integrante-thumb:hover { border-color: var(--accent); transform: translateY(-5px); }

    /* Sección Toques (Grid) */
    .tour-row {
        border-bottom: 1px solid #222;
        padding: 20px 0;
        transition: 0.3s;
    }
    .tour-row:hover { background: #151515; }

    .btn-buy {
        background: transparent;
        border: 2px solid #fff;
        color: #fff;
        font-weight: bold;
        padding: 10px 25px;
        text-transform: uppercase;
    }
    .btn-buy:hover {
        background: var(--accent);
        border-color: var(--accent);
    }

    /* Galería */
    .gallery-item {
        overflow: hidden;
        aspect-ratio: 1 / 1;
        background: #111;
        position: relative;
    }
    .gallery-item img {
        width: 100%; height: 100%; object-fit: cover;
        opacity: 0.7; transition: 0.5s;
    }
    .gallery-item:hover img { opacity: 1; transform: scale(1.1); }
</style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-black sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">LA BANDA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#quienes-somos">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="#redes">Escuchanos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#toques">Toques</a></li>
                    <li class="nav-item"><a class="nav-link" href="#galeria">Galería</a></li>
                </ul>
            </div>
        </div>
    </nav>

<section id="quienes-somos" class="container py-5 mt-5">
    <div class="row align-items-center">
        <div class="col-md-7 mb-4">
            <div class="position-relative">
                <img src="img/cantante.jpg" class="img-fluid img-cantante" alt="Voz">
                <div class="display-1 fw-bold position-absolute bottom-0 start-0 p-4" style="line-height: 0.8; opacity: 0.2;">VOX</div>
            </div>
        </div>
        <div class="col-md-5 ps-md-5">
            <h6 class="text-uppercase text-danger mb-3">// La Banda</h6>
            <h2 class="display-4 mb-4">SONIDO ALTERNATIVO</h2>
            <p class="text-secondary mb-4">Desde los suburbios hasta el escenario principal. Una mezcla de energía cruda y melodías profundas.</p>
            <div class="d-flex gap-2">
                <img src="img/guitarra.jpg" class="integrante-thumb">
                <img src="img/bajo.jpg" class="integrante-thumb">
                <img src="img/bateria.jpg" class="integrante-thumb">
            </div>
        </div>
    </div>
</section>

<section id="toques" class="container py-5">
    <div class="row g-5">
        <div class="col-md-7">
            <h3 class="mb-4 text-uppercase">Historial de Giras</h3>
            <?php
            $historial = $conn->query("SELECT * FROM eventos WHERE es_proximo = 0 ORDER BY fecha DESC");
            while($t = $historial->fetch_assoc()): ?>
                <div class="tour-row d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-danger fw-bold d-block small"><?php echo date('Y', strtotime($t['fecha'])); ?></span>
                        <span class="h5"><?php echo $t['lugar']; ?></span>
                    </div>
                    <div class="text-muted small"><?php echo date('M d', strtotime($t['fecha'])); ?></div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="col-md-5">
            <div class="p-5 border border-secondary text-center shadow-lg" style="background: #000;">
                <h6 class="text-danger fw-bold mb-3">PRÓXIMA FECHA</h6>
                <?php
                $prox = $conn->query("SELECT * FROM eventos WHERE es_proximo = 1 LIMIT 1")->fetch_assoc();
                if($prox): ?>
                    <h2 class="display-5 mb-3"><?php echo $prox['lugar']; ?></h2>
                    <p class="mb-4 text-secondary"><?php echo date('d/m/Y - H:i', strtotime($prox['fecha'])); ?> HS</p>
                    <a href="<?php echo $prox['link_entradas']; ?>" class="btn btn-buy w-100">Obtener Tickets</a>
                <?php else: ?>
                    <p>No hay fechas confirmadas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section id="galeria" class="container-fluid p-0 pt-5">
    <div class="row g-0">
        <div class="col-6 col-md-3">
            <div class="gallery-item">
                <img src="img/show1.jpg">
            </div>
        </div>
        </div>
</section>

    <section id="redes" class="bg-dark section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h4>Spotify</h4>
                    <iframe src="https://open.spotify.com/embed/artist/ID_DE_ARTISTA" width="100%" height="352" frameborder="0" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                </div>
                <div class="col-md-6">
                    <h4>YouTube & Instagram</h4>
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe src="https://www.youtube.com/embed/VIDEO_ID" title="YouTube video" allowfullscreen></iframe>
                    </div>
                    <a href="https://instagram.com/tu_banda" class="btn btn-outline-danger w-100">Seguinos en Instagram</a>
                </div>
            </div>
        </div>
    </section>

    <section id="toques" class="container section-padding">
        <div class="row">
            <div class="col-md-6">
                <h3>Toques Realizados</h3>
                <ul class="list-group list-group-flush">
                    <?php
                    $historial = $conn->query("SELECT * FROM eventos WHERE es_proximo = 0 ORDER BY fecha DESC LIMIT 5");
                    while($row = $historial->fetch_assoc()):
                    ?>
                        <li class="list-group-item bg-transparent text-white-50">
                            <?php echo date('d/m/Y', strtotime($row['fecha'])); ?> - <?php echo $row['lugar']; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <div class="col-md-6 bg-primary p-4 rounded text-center">
                <h3>PRÓXIMO SHOW</h3>
                <?php
                $proximo = $conn->query("SELECT * FROM eventos WHERE es_proximo = 1 LIMIT 1")->fetch_assoc();
                if($proximo): ?>
                    <h4><?php echo $proximo['lugar']; ?></h4>
                    <p><?php echo date('d/m/Y - H:i', strtotime($proximo['fecha'])); ?> hs</p>
                    <a href="<?php echo $proximo['link_entradas']; ?>" class="btn btn-light btn-lg">Comprar Entradas</a>
                <?php else: ?>
                    <p>Próximamente nuevas fechas...</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="container section-padding border-top">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Contacto</h2>
                <form action="" method="POST">
                    <div class="mb-3">
                        <input type="text" name="nombre" class="form-control" placeholder="Tu nombre" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="mensaje" class="form-control" rows="4" placeholder="Mensaje" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>