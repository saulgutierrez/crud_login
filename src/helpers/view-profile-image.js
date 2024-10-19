import PhotoSwipeLightbox from 'https://unpkg.com/photoswipe@5.3.0/dist/photoswipe-lightbox.esm.min.js';
    document.addEventListener('DOMContentLoaded', function () {
        const lightbox = new PhotoSwipeLightbox({
        gallery: 'figure',
        children: 'a',
        pswpModule: () => import('https://unpkg.com/photoswipe@5.3.0/dist/photoswipe.esm.min.js'),
        clickToCloseNonZoomable: true,
        closeOnVerticalDrag: true,
        showHideAnimationType: 'fade',
        arrowPrev: false,
        arrowNext: false,
        close: false,
        counter: false,
    });

    // Prevenir la redirección al hacer clic en el enlace
    document.querySelectorAll('figure a').forEach(function(link) {
        link.addEventListener('click', function(event) {
        event.preventDefault();  // Prevenir la redirección
        });
    });

    lightbox.init();  // Inicializar PhotoSwipe
  });