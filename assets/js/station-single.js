document.addEventListener('DOMContentLoaded', function() {
    const galleryData = document.getElementById('station-gallery-data');
    const galleryImages = galleryData ? JSON.parse(galleryData.textContent || '[]') : [];
    let currentImageIndex = 0;

    function setBodyLocked(locked) {
        document.body.style.overflow = locked ? 'hidden' : '';
    }

    function openLightbox(index) {
        const lightbox = document.getElementById('imageLightbox');
        const image = document.getElementById('lightboxImage');
        const mainImage = document.getElementById('mainGalleryImage');
        if (!lightbox || !image) return;

        currentImageIndex = Number(index) || 0;
        image.src = galleryImages[currentImageIndex] || mainImage?.src || '';
        lightbox.classList.add('active');
        setBodyLocked(true);
    }

    function closeLightbox() {
        document.getElementById('imageLightbox')?.classList.remove('active');
        setBodyLocked(false);
    }

    function openModal() {
        document.getElementById('callback-modal')?.classList.add('active');
        setBodyLocked(true);
    }

    function closeModal() {
        document.getElementById('callback-modal')?.classList.remove('active');
        setBodyLocked(false);
    }

    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.addEventListener('click', function() {
            const mainImage = document.getElementById('mainGalleryImage');
            if (mainImage && this.dataset.imageSrc) {
                mainImage.src = this.dataset.imageSrc;
                mainImage.dataset.galleryIndex = this.dataset.galleryIndex || '0';
            }
            document.querySelectorAll('.gallery-thumb').forEach(item => item.classList.remove('active'));
            this.classList.add('active');
            currentImageIndex = Number(this.dataset.galleryIndex) || 0;
        });
    });

    document.querySelectorAll('.js-open-lightbox').forEach(trigger => {
        trigger.addEventListener('click', () => openLightbox(trigger.dataset.galleryIndex || currentImageIndex));
    });

    document.querySelectorAll('.js-close-lightbox').forEach(trigger => {
        trigger.addEventListener('click', closeLightbox);
    });

    document.getElementById('imageLightbox')?.addEventListener('click', function(event) {
        if (event.target === this) closeLightbox();
    });

    document.querySelectorAll('.js-open-modal').forEach(button => button.addEventListener('click', openModal));
    document.querySelectorAll('.js-close-modal').forEach(button => button.addEventListener('click', closeModal));

    document.querySelectorAll('.js-scroll-to-calc').forEach(button => button.addEventListener('click', function(event) {
        event.preventDefault();
        const target = document.querySelector('#order-form');
        if (!target) return;
        const headerHeight = document.querySelector('.site-header-premium')?.offsetHeight || 100;
        window.scrollTo({
            top: target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20,
            behavior: 'smooth'
        });
    }));

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLightbox();
            closeModal();
        }
    });
});
