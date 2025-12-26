        const galleryContainer = document.getElementById('galleryContainer');
        const prevGallery = document.getElementById('prevGallery');
        const nextGallery = document.getElementById('nextGallery');
        let galleryPosition = 0;
        const cardWidth = 330;

        nextGallery?.addEventListener('click', () => {
            const maxScroll = galleryContainer.scrollWidth - galleryContainer.parentElement.clientWidth;
            galleryPosition = Math.min(galleryPosition + cardWidth, maxScroll);
            galleryContainer.style.transform = `translateX(-${galleryPosition}px)`;
        });

        prevGallery?.addEventListener('click', () => {
            galleryPosition = Math.max(galleryPosition - cardWidth, 0);
            galleryContainer.style.transform = `translateX(-${galleryPosition}px)`;
        });