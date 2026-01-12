<?php
// Datos para SEO
$siteTitle =  $ajustes['nombre_sitio'];
$siteDesc = "Explora la sintonía única de Jose Luis Arriola. Una fusión de Rock, Candombe y Bossa Nova en una experiencia de radio vintage interactiva.";
$siteKeywords = "Jose Luis Arriola, Rock, Candombe, Bossa Nova, Música Interactiva, Sintonía Artística, Wilson Denis Arriola, warriol";
$siteUrl = "https://arriolamusica.com.ar/";
$authorName = "Wilson Denis Arriola";
$authorUrl = "https://warriol.com.uy/";
$ogImage = $siteUrl . "/media/img/og-preview.jpg?v=<?php echo $v; ?>"; // Imagen recomendada 1200x630
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Presencia digital de Jose Luis Arriola, músico y compositor argentino. Su discografía, tour, redes y más.">
<meta name="keywords" content="Jose Luis Arriola, músico, compositor, argentino, discografía, tour, redes">
<meta name="author" content="Wilson Denis Arriola">

<!-- Metadatos Estándar -->
<title><?php echo $siteTitle; ?></title>
<meta name="description" content="<?php echo $siteDesc; ?>">
<meta name="keywords" content="<?php echo $siteKeywords; ?>">
<meta name="author" content="<?php echo $authorName; ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?php echo $siteUrl; ?>">

<!-- Open Graph / Facebook / WhatsApp -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo $siteUrl; ?>">
<meta property="og:title" content="<?php echo $siteTitle; ?>">
<meta property="og:description" content="<?php echo $siteDesc; ?>">
<meta property="og:image" content="<?php echo $ogImage; ?>">

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="<?php echo $siteUrl; ?>">
<meta name="twitter:title" content="<?php echo $siteTitle; ?>">
<meta name="twitter:description" content="<?php echo $siteDesc; ?>">
<meta name="twitter:image" content="<?php echo $ogImage; ?>">

<!-- Atribución de Autoría (Human-readable) -->
<link rel="author" href="<?php echo $authorUrl; ?>">
<meta name="designer" content="<?php echo $authorName; ?>">

<!-- JSON-LD Datos Estructurados (Google Rich Snippets) -->
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "MusicGroup",
      "name": "Jose Luis Arriola",
      "url": "<?php echo $siteUrl; ?>",
      "description": "<?php echo $siteDesc; ?>",
      "genre": ["Rock", "Candombe", "Bossa Nova"],
      "author": {
        "@type": "Person",
        "name": "<?php echo $authorName; ?>",
        "url": "<?php echo $authorUrl; ?>"
      },
      "sameAs": [
        "https://www.instagram.com/josearriola_musico/",
        "https://www.youtube.com/@JoseLuisArriola",
        "https://open.spotify.com/artist/47CJG3RNzKaw1WI5lQ55eB"
      ]
    }
    </script>