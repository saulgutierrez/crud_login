$(document).ready(function () {
    // Evento hover para mostrar y ocultar la fecha formateada
    $('.fecha').hover(function() {
        // Mostrar fecha formateada al pasar el ratón
        $(this).next('.fecha-formateada').show();
    }, function() {
        // Ocultar fecha formateada al quitar el ratón
        $(this).next('.fecha-formateada').hide();
    });
});