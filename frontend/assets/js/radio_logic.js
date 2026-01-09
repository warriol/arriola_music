/**
 * ARCHIVO: frontend/assets/js/radio_logic.js
 * Descripción: Gestión profesional de audio, sintonía y efectos visuales.
 */

document.addEventListener('DOMContentLoaded', () => {
    // --- UI ELEMENTS ---
    const needleContainer = document.getElementById('needleContainer');
    const tuningKnob = document.getElementById('tuningKnob');
    const powerBtn = document.getElementById('powerBtn');
    const powerLed = document.getElementById('powerLed');
    const sections = document.querySelectorAll('section');

    // --- AUDIO ASSETS ---
    const audioAssets = {
        estatica: new Audio('media/music/static.mp3'),
        artista: new Audio()
    };

    // Configuración inicial de carga para evitar retardos
    audioAssets.estatica.preload = "auto";
    audioAssets.artista.preload = "auto";
    audioAssets.estatica.loop = true;
    audioAssets.estatica.volume = 0;
    audioAssets.artista.loop = true;

    let isPowerOn = false;
    let currentSectionIndex = -1;

    /**
     * Calcula y aplica los volúmenes según la posición del scroll
     * Se ejecuta tanto al hacer scroll como INSTANTÁNEAMENTE al encender la radio.
     */
    function actualizarFrecuencias() {
        const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (totalHeight <= 0) return;

        const scrollPos = window.pageYOffset;
        const scrollPercent = (scrollPos / totalHeight) * 100;

        // 1. Efecto Visual: Aguja y Perilla
        if (needleContainer) needleContainer.style.left = `${Math.min(Math.max(scrollPercent, 0), 100)}%`;
        if (tuningKnob) tuningKnob.style.transform = `rotate(${(scrollPos / totalHeight) * 1800}deg)`;

        if (!isPowerOn) return;

        // 2. Lógica de Sección (Sintonía de canciones)
        let activeIndex = 0;
        sections.forEach((section, i) => {
            const rect = section.getBoundingClientRect();
            if (rect.top < window.innerHeight / 2 && rect.bottom > window.innerHeight / 2) {
                activeIndex = i;
            }
        });

        if (activeIndex !== currentSectionIndex) {
            cambiarCancion(activeIndex);
            currentSectionIndex = activeIndex;
        }

        // 3. Modulación de Estática:
        // Solo genera ruido si estamos en movimiento brusco o cambiando de dial.
        // Se establece la base en 0 para que no haya siseo de fondo constante.
        let flicker = Math.abs(Math.sin(scrollPos / 100)) * 0.05;
        audioAssets.estatica.volume = Math.min(0.2, 0 + flicker);
    }

    /**
     * Cambia la fuente del audio según la sección
     */
    function cambiarCancion(index) {
        if (!isPowerOn || index === currentSectionIndex) return;

        const songNumber = (index + 1).toString().padStart(2, '0');
        const nuevaRuta = `media/music/cancion_${songNumber}.mp3`;

        if (audioAssets.artista.src !== window.location.origin + '/' + nuevaRuta) {
            audioAssets.artista.src = nuevaRuta;
            audioAssets.artista.load();
            audioAssets.artista.play().catch(() => {});
        }

        // Efecto de sintonía: sube estática un momento para simular el dial captando la señal
        const volOriginalMusica = 0.7;
        audioAssets.artista.volume = 0.3;
        audioAssets.estatica.volume = 0.1;

        // Tras 500ms, la sintonía se estabiliza y la estática se apaga (volumen 0)
        setTimeout(() => {
            if (isPowerOn) {
                audioAssets.artista.volume = volOriginalMusica;
                audioAssets.estatica.volume = 0;
            }
        }, 500);
    }

    /**
     * Alterna el encendido de la radio e inicia el audio inmediatamente
     */
    function togglePower() {
        isPowerOn = !isPowerOn;

        if (isPowerOn) {
            // Sincronización con el botón VERDE
            if (powerLed) {
                powerLed.style.backgroundColor = "#22c55e";
                powerLed.style.boxShadow = "0 0 10px #22c55e";
            }

            // Inicio inmediato de audio
            audioAssets.estatica.play().catch(e => console.log("Audio estática esperando interacción..."));

            // Sintonizar inmediatamente la canción de la posición actual
            actualizarFrecuencias();
        } else {
            // Sincronización con el botón ROJO (LED apagado/tenue)
            if (powerLed) {
                powerLed.style.backgroundColor = "#7f1d1d";
                powerLed.style.boxShadow = "none";
            }

            // Apagado total de audio
            audioAssets.estatica.pause();
            audioAssets.artista.pause();
            currentSectionIndex = -1; // Reiniciamos índice para re-sintonizar al encender
        }
    }

    // --- LISTENERS ---

    powerBtn?.addEventListener('click', togglePower);

    window.addEventListener('scroll', actualizarFrecuencias);
    window.addEventListener('resize', actualizarFrecuencias);
    // Inicializar volúmenes y posiciones
    actualizarFrecuencias();

    // --- YOUTUBE IFRAME CONFIG ---
    document.querySelectorAll('iframe[src*="youtube"]').forEach(iframe => {
        iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
        iframe.setAttribute('referrerpolicy', 'no-referrer-when-downgrade');
    });
});