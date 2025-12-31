<?php
/**
 * SECCIÓN 05: MULTIMEDIA (ID: s5)
 * Descripción: Galería dinámica de conciertos y folletos con visualización ampliada.
 */

// Instanciamos el modelo de Galería
$galeriaModel = new Galeria();

// Obtenemos las fotos de conciertos/folletos marcadas como visibles
$fotosMultimedia = $galeriaModel->listar(true);
?>
<!-- SECCIÓN 05: MULTIMEDIA -->
<section id="s5">
    <div class="absolute top-24 text-center w-full">
        <h2 class="text-3xl font-black mb-10 text-amber-600 tracking-widest bg-black/40 px-6 py-2 border-b-2 border-amber-900 uppercase">
            Archivo Multimedia
        </h2>
    </div>

    <!-- Frame del Monitor -->
    <div class="multimedia-monitor-frame mt-20">
        <!-- Contenedor con scroll vertical -->
        <div class="p-6 h-full overflow-y-auto custom-scrollbar bg-black/20">
            <?php if (empty($fotosMultimedia)): ?>
                <div class="flex items-center justify-center h-full">
                    <p class="text-zinc-500 italic uppercase tracking-widest text-sm">
                        Sin capturas en el archivo de conciertos...
                    </p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <?php foreach ($fotosMultimedia as $foto): ?>
                        <div class="gallery-item-wrapper group relative cursor-pointer overflow-hidden border-2 border-amber-900/30 hover:border-amber-500 transition-colors"
                             onclick="openLightbox('media/galeria/<?php echo $foto['url_imagen']; ?>', '<?php echo htmlspecialchars($foto['pie_de_foto']); ?>')">

                            <img src="media/galeria/<?php echo htmlspecialchars($foto['url_imagen']); ?>"
                                 alt="<?php echo htmlspecialchars($foto['pie_de_foto']); ?>"
                                 class="w-full h-40 object-cover grayscale hover:grayscale-0 transition-all duration-500 transform group-hover:scale-110">

                            <div class="absolute bottom-0 left-0 right-0 bg-black/80 p-2 translate-y-full group-hover:translate-y-0 transition-transform">
                                <p class="text-[9px] text-amber-500 font-bold uppercase truncate text-center">
                                    <?php echo htmlspecialchars($foto['pie_de_foto']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- MODAL LIGHTBOX (Oculto por defecto) -->
    <div id="multimediaLightbox" class="fixed inset-0 z-[1000] hidden bg-black/95 flex-col items-center justify-center p-4 backdrop-blur-sm" onclick="closeLightbox()">
        <button class="absolute top-10 right-10 text-amber-500 text-4xl font-bold hover:text-white transition-colors">&times;</button>

        <div class="max-w-5xl w-full flex flex-col items-center" onclick="event.stopPropagation()">
            <img id="lightboxImg" src="" alt="Ampliada" class="max-h-[80vh] border-4 border-amber-900/50 shadow-2xl">
            <p id="lightboxCaption" class="mt-4 text-amber-500 font-bold uppercase tracking-widest text-lg bg-black/60 px-4 py-2"></p>
        </div>
    </div>
</section>

<style>
    /* Estilo específico para el monitor multimedia */
    .custom-scrollbar::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #000; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4e342e; border-radius: 0px; border: 1px solid #1a0f08; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #ffb347; }

    /* Animación de entrada para el modal */
    #multimediaLightbox.active { display: flex; animation: fadeIn 0.3s ease-out; }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<script>
    /**
     * Lógica de la Galería Multimedia
     */
    function openLightbox(url, caption) {
        const lightbox = document.getElementById('multimediaLightbox');
        const img = document.getElementById('lightboxImg');
        const cap = document.getElementById('lightboxCaption');

        img.src = url;
        cap.innerText = caption;
        lightbox.classList.add('active');

        // Bloquear scroll del body al abrir
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lightbox = document.getElementById('multimediaLightbox');
        lightbox.classList.remove('active');

        // Restaurar scroll
        document.body.style.overflow = 'auto';
    }

    // Cerrar con la tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLightbox();
    });
</script>