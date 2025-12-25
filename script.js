        const needleContainer = document.getElementById('needleContainer');
        const tuningKnob = document.getElementById('tuningKnob');
        const powerBtn = document.getElementById('powerBtn');
        const powerLed = document.getElementById('powerLed');
        const staticSound = document.getElementById('staticSound');
        const artistMusic = document.getElementById('artistMusic');
        const sections = document.querySelectorAll('section');

        const galleryContainer = document.getElementById('galleryContainer');
        const prevGallery = document.getElementById('prevGallery');
        const nextGallery = document.getElementById('nextGallery');
        let galleryPosition = 0;
        const cardWidth = 330;

        let isPowerOn = false;
        let currentSectionIndex = -1;

        // --- Galería ---
        nextGallery?.addEventListener('click', () => {
            const maxScroll = galleryContainer.scrollWidth - galleryContainer.parentElement.clientWidth;
            galleryPosition = Math.min(galleryPosition + cardWidth, maxScroll);
            galleryContainer.style.transform = `translateX(-${galleryPosition}px)`;
        });

        prevGallery?.addEventListener('click', () => {
            galleryPosition = Math.max(galleryPosition - cardWidth, 0);
            galleryContainer.style.transform = `translateX(-${galleryPosition}px)`;
        });

        function playSongForSection(index) {
            if (!isPowerOn) return;
            const songNumber = (index + 1).toString().padStart(2, '0');
            artistMusic.src = `music/cancion_${songNumber}.mp3`;
            artistMusic.volume = 0.7;
            artistMusic.play().catch(() => { });
            staticSound.volume = 0.3;
            setTimeout(() => staticSound.volume = 0.05, 400);
        }

        window.addEventListener('scroll', () => {
            const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (window.pageYOffset / totalHeight) * 100;
            needleContainer.style.left = `${Math.min(Math.max(scrollPercent, 0), 100)}%`;
            tuningKnob.style.transform = `rotate(${(window.pageYOffset / totalHeight) * 1800}deg)`;

            let index = 0;
            sections.forEach((section, i) => {
                const rect = section.getBoundingClientRect();
                if (rect.top < window.innerHeight / 2 && rect.bottom > window.innerHeight / 2) {
                    index = i;
                }
            });

            if (index !== currentSectionIndex) {
                if (isPowerOn) playSongForSection(index);
                currentSectionIndex = index;
            }
        });

        powerBtn.addEventListener('click', () => {
            isPowerOn = !isPowerOn;
            powerBtn.classList.toggle('is-pushed');
            if (isPowerOn) {
                powerLed.style.backgroundColor = "#ff0000";
                powerLed.style.boxShadow = "0 0 10px #ff0000";
                staticSound.play();
                playSongForSection(currentSectionIndex === -1 ? 0 : currentSectionIndex);
            } else {
                powerLed.style.backgroundColor = "#450000";
                powerLed.style.boxShadow = "none";
                staticSound.pause();
                artistMusic.pause();
            }
        });