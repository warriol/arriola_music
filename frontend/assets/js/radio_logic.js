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

    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes titileoPower {
            0% { box-shadow: 0 4px 0 #7f1d1d, 0 0 5px rgba(185, 28, 28, 0.4); opacity: 1; }
            50% { box-shadow: 0 4px 0 #7f1d1d, 0 0 20px rgba(185, 28, 28, 0.8); opacity: 0.8; }
            100% { box-shadow: 0 4px 0 #7f1d1d, 0 0 5px rgba(185, 28, 28, 0.4); opacity: 1; }
        }
        .btn-power-off.pulse-active {
            animation: titileoPower 1.5s infinite ease-in-out;
        }
    `;
    document.head.appendChild(style);

    function actualizarFrecuencias() {
        const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (totalHeight <= 0) return;

        const scrollPos = window.scrollY;
        const scrollPercent = (scrollPos / totalHeight);

        // --- LÓGICA VISUAL (Siempre activa) ---
        if (needleContainer) {
            const margin = 6;
            const range = 100 - (margin * 2);
            const needlePos = margin + (scrollPercent * range);
            needleContainer.style.left = `${needlePos}%`;
        }

        if (tuningKnob) {
            tuningKnob.style.transform = `rotate(${scrollPercent * 1800}deg)`;
        }

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
            powerBtn.classList.remove('btn-power-off');
            powerBtn.classList.remove('pulse-active');
            powerBtn.classList.add('btn-power-on');
            powerBtn.innerText = "ON";

            if (powerLed) {
                powerLed.style.backgroundColor = "#22c55e";
                powerLed.style.boxShadow = "0 0 10px #22c55e";
            }

            audioAssets.estatica.play().catch(() => {});
            actualizarFrecuencias();
        } else {
            powerBtn.classList.remove('btn-power-on');
            powerBtn.classList.add('btn-power-off');
            powerBtn.classList.add('pulse-active');
            powerBtn.innerText = "OFF";

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

    if (!isPowerOn && powerBtn) {
        powerBtn.classList.add('pulse-active');
    }

    actualizarFrecuencias();

    document.querySelectorAll('iframe[src*="youtube"]').forEach(iframe => {
        iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
        iframe.setAttribute('referrerpolicy', 'no-referrer-when-downgrade');
    });
});