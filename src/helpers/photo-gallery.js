document.addEventListener('DOMContentLoaded', function () {
    // Inicializar PhotoSwipe con PhotoSwipeLightbox
    const lightbox = new PhotoSwipeLightbox({
        gallery: '.my-gallery', // El contenedor de la galería
        children: 'a', // Los enlaces que contienen las imágenes
        pswpModule: () => import('https://unpkg.com/photoswipe@5.3.8/dist/photoswipe.esm.js')
    });

    // Iniciar el lightbox
    lightbox.init();
});;
