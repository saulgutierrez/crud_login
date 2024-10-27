window.onload = function () {
    const lightbox = new PhotoSwipeLightbox({
        gallery: '.my-gallery',
        children: 'a',
        initialZoomLevel: 'fit',
        secondaryZoomLevel: 1.5,
        maxZoomLevel: 1,
        pswpModule: PhotoSwipe,
        wheelToZoom: true
    });

    lightbox.init();
    console.log('PhotoSwipe Lightbox initialized');
};