<?php
/**
 * SECCIÓN 06: REDES SOCIALES (ID: s6)
 * Descripción: Placa metálica con los diales de conexión a redes sociales.
 */

$urlInstagram = $ajustes['instagram_url'] ?? '#';
$urlYoutube   = $ajustes['youtube_url'] ?? '#';
$urlFacebook  = $ajustes['facebook_url'] ?? '#';
$urlSpotify   = $ajustes['spotify_url'] ?? '#';
$urlLinktree  = $ajustes['linktree_url'] ?? '#';
$youtubeId    = $ajustes['youtube_id'] ?? '#';
?>
<!-- SECCIÓN 06: REDES SOCIALES (ID: s6) -->
<section id="s6">
    <h2
        class="text-3xl font-black mb-10 text-amber-600 tracking-widest bg-black/40 px-6 py-2 border-b-2 border-amber-900">
        REDES SOCIALES</h2>

    <div class="footer-video-frame">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $youtubeId; ?>"
            title="YouTube video player" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>

    <div class="bottom-wood-base">
        <div class="metal-plate">
            <div class="screw screw-tl"></div>
            <div class="screw screw-tr"></div>
            <div class="screw screw-bl"></div>
            <div class="screw screw-br"></div>
            <div class="social-links">
                <a href="<?php echo $urlInstagram; ?>" target="_blank" class="social-icon">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8A3.6 3.6 0 0 0 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6A3.6 3.6 0 0 0 16.4 4H7.6m9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8 1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3z" />
                    </svg>
                    <span class="social-label">Insta</span>
                </a>
                <a href="<?php echo $urlYoutube; ?>" target="_blank" class="social-icon">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 19c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73.47-.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 5c4.19 0 6.8.16 7.83.44.9.25 1.48.83 1.73 1.73z" />
                    </svg>
                    <span class="social-label">YouTube</span>
                </a>
                <a href="<?php echo $urlFacebook; ?>" target="_blank" class="social-icon">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M12 2.04c-5.5 0-10 4.49-10 10.02 0 5 3.66 9.15 8.44 9.9v-7h-2.54v-2.9h2.54V9.82c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.19 2.23.19v2.47h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.45 2.9h-2.33v7a10 10 0 0 0 8.44-9.9c0-5.53-4.5-10.02-10-10.02z" />
                    </svg>
                    <span class="social-label">Face</span>
                </a>
                <a href="<?php echo $urlSpotify; ?>" target="_blank" class="social-icon">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.508 17.302c-.218.358-.684.474-1.042.255-2.853-1.743-6.444-2.138-10.673-1.171-.41.094-.823-.16-.917-.57-.094-.41.16-.823.57-.917 4.636-1.06 8.608-.61 11.799 1.342.358.218.474.684.255 1.042zm1.47-3.253c-.275.446-.853.587-1.3.312-3.264-2.006-8.239-2.59-12.098-1.417-.502.152-1.033-.133-1.185-.635-.152-.502.133-1.033.635-1.185 4.414-1.338 9.914-.683 13.635 1.605.447.275.588.853.313 1.3zm.127-3.41c-3.913-2.325-10.364-2.541-14.126-1.4c-.6.182-1.233-.158-1.415-.758-.182-.6.158-1.233.758-1.415 4.314-1.31 11.444-1.05 15.96 1.63.54.32.716 1.014.397 1.554-.319.54-1.013.717-1.554.398z" />
                    </svg>
                    <span class="social-label">Spotify</span>
                </a>
            </div>
            <a href="<?php echo $urlLinktree; ?>" target="_blank" class="linktree-btn">VISITAR LINKTREE
                OFICIAL</a>
        </div>

        <div class="engraved-text">
            Copyright &copy; desde 2025 - <a href="https://warriol.com.uy" target="_blank">Wilson Denis
                Arriola</a>
        </div>
    </div>
</section>