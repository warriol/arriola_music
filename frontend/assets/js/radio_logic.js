/**
 * ARCHIVO: frontend/assets/js/radio_logic.js
 * Descripción: Gestión profesional de audio, sintonía y efectos visuales.
 */

document.addEventListener('DOMContentLoaded', () => {
    const needleContainer = document.getElementById('needleContainer');
    const tuningKnob = document.getElementById('tuningKnob');
    const powerBtn = document.getElementById('powerBtn');
    const powerLed = document.getElementById('powerLed');
    const sections = document.querySelectorAll('section');

    const audioAssets = {
        estatica: new Audio('media/music/static.mp3'),
        artista: new Audio()
    };

    audioAssets.estatica.preload = "auto";
    audioAssets.artista.preload = "auto";
    audioAssets.estatica.loop = true;
    audioAssets.estatica.volume = 0;
    audioAssets.artista.loop = true;

    let isPowerOn = false;
    let currentSectionIndex = -1;

    function actualizarFrecuencias() {
        const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (totalHeight <= 0) return;

        const scrollPos = window.pageYOffset;
        const scrollPercent = (scrollPos / totalHeight) * 100;

        if (needleContainer) needleContainer.style.left = `${Math.min(Math.max(scrollPercent, 5), 95)}%`;
        if (tuningKnob) tuningKnob.style.transform = `rotate(${(scrollPos / totalHeight) * 1800}deg)`;

        if (!isPowerOn) return;

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

        let flicker = Math.abs(Math.sin(scrollPos / 100)) * 0.05;
        audioAssets.estatica.volume = Math.min(0.2, 0 + flicker);
    }

    function cambiarCancion(index) {
        audioAssets.estatica.volume = 0.08;

        if (!isPowerOn) return;

        const songNumber = (index + 1).toString().padStart(2, '0');
        const nuevaRuta = `media/music/cancion_${songNumber}.mp3`;

        if (audioAssets.artista.src !== window.location.origin + '/' + nuevaRuta) {
            audioAssets.artista.src = nuevaRuta;
            audioAssets.artista.load();
            audioAssets.artista.play().catch(() => {});
        }

        const volOriginalMusica = 0.8;
        audioAssets.artista.volume = 0.5;

        setTimeout(() => {
            if (isPowerOn) {
                audioAssets.artista.volume = volOriginalMusica;
                audioAssets.estatica.volume = 0;
            }
        }, 500);
    }

    function togglePower() {
        isPowerOn = !isPowerOn;

        if (isPowerOn) {
            if (powerLed) {
                powerLed.style.backgroundColor = "#22c55e";
                powerLed.style.boxShadow = "0 0 10px #22c55e";
            }

            audioAssets.estatica.play().catch(e => console.log("Audio estática esperando interacción..."));

            actualizarFrecuencias();
        } else {
            if (powerLed) {
                powerLed.style.backgroundColor = "#7f1d1d";
                powerLed.style.boxShadow = "none";
            }

            audioAssets.estatica.pause();
            audioAssets.artista.pause();
            currentSectionIndex = -1;
        }
    }

    powerBtn?.addEventListener('click', togglePower);

    window.addEventListener('scroll', actualizarFrecuencias);
    window.addEventListener('resize', actualizarFrecuencias);

    actualizarFrecuencias();

    document.querySelectorAll('iframe[src*="youtube"]').forEach(iframe => {
        iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
        iframe.setAttribute('referrerpolicy', 'no-referrer-when-downgrade');
    });
});