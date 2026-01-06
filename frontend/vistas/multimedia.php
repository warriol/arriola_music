<?php
/**
 * SECCIÓN 05: MULTIMEDIA (ID: s5) - VERSIÓN ÁLBUMES
 * Descripción: Muestra álbumes temáticos. Al sintonizar uno, se despliegan sus capturas.
 */

// Instanciamos el modelo de Galería
$galeriaModel = new Galeria();

// Obtenemos la lista de álbumes visibles
$albumes = $galeriaModel->getAlbumes(true);
?>
<!-- SECCIÓN 05: MULTIMEDIA -->
<section id="s5">
    <h2 class="text-3xl font-black mb-10 text-amber-600 tracking-widest bg-black/40 px-6 py-2 border-b-2 border-amber-900 uppercase">
        Frecuencias multimedia
    </h2>

    <div class="multimedia-monitor-frame mt-20">
        <div id="albumViewContainer" class="p-6 h-full overflow-y-auto custom-scrollbar bg-black/20">

            <?php if (empty($albumes)): ?>
                <div class="flex flex-col items-center justify-center h-full text-zinc-600">
                    <span class="text-5xl mb-4">📻</span>
                    <p class="uppercase tracking-widest text-xs italic">Buscando señal de archivos...</p>
                </div>
            <?php else: ?>
                <!-- Grid de Álbumes -->
                <div id="publicAlbumGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                    <?php foreach ($albumes as $album): ?>
                        <div class="album-folder group cursor-pointer" onclick="sintonizarAlbum(<?php echo $album['id']; ?>, '<?php echo htmlspecialchars($album['nombre']); ?>')">
                            <div class="folder-icon relative bg-[#2d1b0e] border-2 border-amber-900/50 p-4 transition-all group-hover:border-amber-500 group-hover:bg-[#3d2b1f] shadow-lg">
                                <!-- Pestaña de carpeta -->
                                <div class="absolute -top-3 left-0 w-12 h-3 bg-[#2d1b0e] border-t-2 border-x-2 border-amber-900/50 group-hover:bg-[#3d2b1f] group-hover:border-amber-500"></div>

                                <div class="flex items-center justify-center h-28 mb-2 overflow-hidden bg-black/40">
                                    <?php if (!empty($album['portada'])): ?>
                                        <img src="media/galeria/<?php echo $album['portada']; ?>"
                                             class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity duration-500 filter sepia(0.3) group-hover:sepia-0">
                                    <?php else: ?>
                                        <span class="text-4xl filter grayscale group-hover:grayscale-0 transition-all">📁</span>
                                    <?php endif; ?>
                                </div>

                                <h3 class="text-amber-500 font-bold uppercase text-xs text-center tracking-tighter truncate">
                                    <?php echo htmlspecialchars($album['nombre']); ?>
                                </h3>
                                <p class="text-[9px] text-zinc-500 text-center uppercase mt-1">
                                    <?php echo htmlspecialchars($album['descripcion']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- MODAL 1: VISOR DE FOTOS -->
    <div id="albumPhotosModal" class="fixed inset-0 z-[1000] hidden bg-black/95 flex-col items-center justify-start p-6 backdrop-blur-md overflow-y-auto" onclick="cerrarAlbum()">
        <div class="w-full max-w-6xl mt-10" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-8 border-b border-amber-900/50 pb-4">
                <div class="flex items-center gap-4">
                    <span class="text-amber-500 text-2xl">📻</span>
                    <h2 id="modalAlbumTitle" class="text-3xl font-bold text-amber-500 uppercase tracking-tighter"></h2>
                </div>
                <button onclick="cerrarAlbum()" class="text-amber-500 text-4xl hover:text-white">&times;</button>
            </div>
            <div id="publicPhotosGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 pb-20"></div>
        </div>
    </div>

    <!-- MODAL 2: ZOOM -->
    <div id="photoZoomModal" class="fixed inset-0 z-[2000] hidden bg-black/90 items-center justify-center p-4 backdrop-blur-sm cursor-zoom-out" onclick="cerrarZoom()">
        <div class="relative max-w-5xl w-full flex flex-col items-center" onclick="event.stopPropagation()">
            <div class="bg-white p-3 pb-12 shadow-2xl transform rotate-1">
                <img id="zoomedImage" src="" class="max-w-full max-h-[75vh] object-contain" alt="Zoom">
                <p id="zoomedCaption" class="text-black font-bold text-center mt-6 uppercase tracking-widest text-sm italic"></p>
            </div>
            <button onclick="cerrarZoom()" class="absolute -top-10 -right-2 text-white text-5xl hover:text-amber-500">&times;</button>
        </div>
    </div>
</section>

<style>
    .album-folder { perspective: 1000px; }
    .folder-icon { transform-style: preserve-3d; transition: transform 0.5s; }
    .album-folder:hover .folder-icon { transform: rotateX(10deg); }

    .public-photo-card {
        background: #fff;
        padding: 10px 10px 30px 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
    }

    .public-photo-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        filter: sepia(0.2) contrast(1.1);
    }

    .public-photo-card:hover { transform: scale(1.1) rotate(0deg) !important; z-index: 50; }
    .public-photo-card:hover img { filter: sepia(0) contrast(1.2); }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #1a0f08; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4e342e; border-radius: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #ffb347; }
</style>

<script>
    async function sintonizarAlbum(id, nombre) {
        const modal = document.getElementById('albumPhotosModal');
        const grid = document.getElementById('publicPhotosGrid');
        const title = document.getElementById('modalAlbumTitle');

        title.innerText = nombre;
        grid.innerHTML = '<p class="col-span-full text-center text-amber-900 animate-pulse py-20 uppercase tracking-widest text-xs">Sintonizando archivos...</p>';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        try {
            const response = await fetch(`backend/galeria/listarFotos?album_id=${id}`);
            const result = await response.json();
            if (result.status === 'success') {
                grid.innerHTML = result.data.map(foto => `
                <div class="public-photo-card"
                     style="transform: rotate(${(Math.random() * 6 - 3).toFixed(1)}deg)"
                     onclick="abrirZoom('media/galeria/${foto.url_imagen}', '${foto.pie_de_foto.replace(/'/g, "\\'")}')">
                    <img src="media/galeria/${foto.url_imagen}" alt="">
                    <p class="text-[9px] text-black font-bold text-center mt-3 uppercase truncate">${foto.pie_de_foto}</p>
                </div>
            `).join('');
            }
        } catch (error) { console.error(error); }
    }

    function abrirZoom(url, caption) {
        document.getElementById('zoomedImage').src = url;
        document.getElementById('zoomedCaption').innerText = caption;
        document.getElementById('photoZoomModal').classList.remove('hidden');
        document.getElementById('photoZoomModal').classList.add('flex');
    }

    function cerrarZoom() { document.getElementById('photoZoomModal').classList.add('hidden'); }
    function cerrarAlbum() {
        document.getElementById('albumPhotosModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { cerrarZoom(); cerrarAlbum(); }
    });
</script>