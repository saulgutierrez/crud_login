import PhotoSwipeLightbox from 'https://unpkg.com/photoswipe@5.3.0/dist/photoswipe-lightbox.esm.min.js';

document.addEventListener('DOMContentLoaded', function () {
    const lightbox = new PhotoSwipeLightbox({
        gallery: 'figure',  // El selector para el contenedor de galería
        children: 'a',  // El selector para los enlaces de imágenes
        pswpModule: () => import('https://unpkg.com/photoswipe@5.3.0/dist/photoswipe.esm.min.js'), // Carga el módulo
        clickToCloseNonZoomable: true,
        closeOnVerticalDrag: true,
        showHideAnimationType: 'fade',
    });

    // Prevenir la redirección al hacer clic en el enlace
    document.querySelectorAll('figure a').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();  // Prevenir la redirección
        });
    });

    lightbox.init();  // Inicializar PhotoSwipe
});