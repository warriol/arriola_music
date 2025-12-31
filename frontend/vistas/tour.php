<?php
/**
 * SECCIÓN 03: TOUR (ID: s3)
 * Descripción: Muestra las fechas de conciertos sintonizadas desde la base de datos.
 */

// Instanciamos el modelo de Tour
$tourModel = new Tour();

// Obtenemos solo los eventos marcados como visibles
$eventos = $tourModel->listar(true);

/**
 * Función auxiliar para formatear fechas de MySQL a formato vintage (DD MES YYYY)
 */
function formatearFechaVintage($fecha) {
    $meses = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"];
    $time = strtotime($fecha);
    $dia = date('d', $time);
    $mes = $meses[date('n', $time) - 1];
    $anio = date('Y', $time);
    return "$dia $mes $anio";
}
?>
<!-- SECCIÓN 03: TOUR (ID: s3) -->
<section id="s3">
    <h2
        class="text-3xl font-black tracking-widest text-amber-800 bg-black/60 inline-block px-8 py-2 border-b-2 border-amber-900">
        TOUR</h2>
    <div class="tour-table-container">
        <table class="tour-table">
            <thead>
                <tr class="tour-header">
                    <th>FECHA</th>
                    <th>LOCAL / FUNCIÓN</th>
                    <th>ENTRADAS</th>
                    <th>COMUNIDAD</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($eventos)): ?>
                <tr>
                    <td colspan="4" class="text-center py-20 text-zinc-500 italic">
                        Buscando frecuencias... Próximamente nuevas fechas.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($eventos as $show):
                    $esPasado = strtotime($show['fecha']) < strtotime(date('Y-m-d'));
                    ?>
                    <tr class="tour-row <?php echo $esPasado ? 'opacity-50' : ''; ?>">
                        <td class="tour-date">
                            <?php echo formatearFechaVintage($show['fecha']); ?>
                        </td>
                        <td class="tour-venue-info">
                            <b><?php echo htmlspecialchars($show['lugar']); ?></b>
                            <i>"<?php echo htmlspecialchars($show['descripcion']); ?>"</i>
                            <span><?php echo htmlspecialchars($show['direccion']); ?></span>
                            <?php if (!empty($show['direccion'])): ?>
                                <a href="https://maps.google.com/?q=<?php echo urlencode($show['direccion']); ?>"
                                   target="_blank" class="text-[10px] text-amber-600 hover:underline">📍 Ubicación</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($esPasado): ?>
                                <button class="btn-action btn-disabled uppercase">Finalizado</button>
                            <?php elseif (!empty($show['url_tickets'])): ?>
                                <a href="<?php echo $show['url_tickets']; ?>" target="_blank" class="btn-action block w-full">Comprar</a>
                            <?php else: ?>
                                <button class="btn-action btn-disabled uppercase">Próximamente</button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($show['hashtag'])): ?>
                                <a href="https://www.instagram.com/explore/tags/<?php echo str_replace('#', '', $show['hashtag']); ?>"
                                   target="_blank" class="btn-action block w-full bg-zinc-800">
                                    #<?php echo htmlspecialchars($show['hashtag']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-zinc-700 text-[10px] text-center block italic">Sin señal social</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>