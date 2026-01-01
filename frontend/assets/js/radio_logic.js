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
    // Pre-load base audios to avoid repeated requests
    const audioAssets = {
        estatica: new Audio('media/music/tv-static-05.mp3'),
        artista: new Audio() // Loaded dynamically based on section
    };

    // Initial configuration
    audioAssets.estatica.loop = true;
    audioAssets.estatica.volume = 0;
    audioAssets.artista.volume = 0;

    let isPowerOn = false;
    let currentSectionIndex = -1;
    let audioIniciado = false;

    /**
     * Starts the audio system (Required by Chrome policies)
     */
    async function iniciarAudioSistema() {
        if (audioIniciado || !isPowerOn) return;

        try {
            await audioAssets.estatica.play();
            audioIniciado = true;
            console.log("Audio tuning initiated.");
        } catch (err) {
            console.log("Waiting for user interaction for audio...");
        }
    }

    /**
     * Changes the song based on the tuned section (s1 -> 01.mp3, etc.)
     */
    function sintonizarCancion(index) {
        if (!isPowerOn || index === currentSectionIndex) return;

        // Map index (0-5) to file names (01-06)
        const songNumber = (index + 1).toString().padStart(2, '0');
        const nuevaRuta = `media/music/cancion_${songNumber}.mp3`;

        // Only change if the path is different to avoid unnecessary buffer restarts
        if (audioAssets.artista.src !== window.location.origin + '/' + nuevaRuta) {
            audioAssets.artista.src = nuevaRuta;
            audioAssets.artista.load();
            audioAssets.artista.play().catch(() => {});
        }

        // Tuning effect: Raise static briefly when switching songs
        audioAssets.estatica.volume = 0.4;
        audioAssets.artista.volume = 0.1;

        // Once tuned, silence static and raise music volume
        setTimeout(() => {
            if (isPowerOn) {
                audioAssets.estatica.volume = 0; // Silence static once the song starts
                audioAssets.artista.volume = 0.7;
            }
        }, 600);
    }

    // --- CONTROL LISTENERS ---

    // Power On / Off
    powerBtn?.addEventListener('click', () => {
        isPowerOn = !isPowerOn;
        powerBtn.classList.toggle('is-pushed');

        if (isPowerOn) {
            powerLed.style.backgroundColor = "#ff0000";
            powerLed.style.boxShadow = "0 0 10px #ff0000";
            iniciarAudioSistema();
            if (currentSectionIndex !== -1) sintonizarCancion(currentSectionIndex);
        } else {
            powerLed.style.backgroundColor = "#450000";
            powerLed.style.boxShadow = "none";
            audioAssets.estatica.pause();
            audioAssets.artista.pause();
            audioIniciado = false;
        }
    });

    // Audio unlock for modern browsers
    document.addEventListener('click', iniciarAudioSistema, { once: false });

    // Scroll and Modulation
    window.addEventListener('scroll', () => {
        const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (totalHeight <= 0) return;

        const scrollPos = window.pageYOffset;
        const scrollPercent = (scrollPos / totalHeight) * 100;

        // Visual: Needle and Knob
        if (needleContainer) needleContainer.style.left = `${Math.min(Math.max(scrollPercent, 0), 100)}%`;
        if (tuningKnob) tuningKnob.style.transform = `rotate(${(scrollPos / totalHeight) * 1800}deg)`;

        // Section Logic
        let activeIndex = 0;
        sections.forEach((section, i) => {
            const rect = section.getBoundingClientRect();
            if (rect.top < window.innerHeight / 2 && rect.bottom > window.innerHeight / 2) {
                activeIndex = i;
            }
        });

        if (activeIndex !== currentSectionIndex) {
            sintonizarCancion(activeIndex);
            currentSectionIndex = activeIndex;
        }

        // Optional static flicker during active scrolling between sections
        if (isPowerOn && audioAssets.estatica.volume > 0) {
            let flicker = Math.abs(Math.sin(scrollPos / 100)) * 0.05;
            audioAssets.estatica.volume = Math.min(0.4, audioAssets.estatica.volume + flicker);
        }
    });

    /**
     * FIX FOR YOUTUBE IN FIREFOX
     */
    document.querySelectorAll('iframe[src*="youtube"]').forEach(iframe => {
        iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
        iframe.setAttribute('referrerpolicy', 'no-referrer-when-downgrade');
    });
});