$(document).ready(function () {
    var fecha = $('.fecha');
    fecha.each(function () {
        this.addEventListener('mouseover', function () {
            var fecha = $(this).data('fecha');
            const fechaJS = dateFns.parse(fecha, "dd-MM-yyyy HH:mm:ss", new Date());
            // Mostrar fecha formateada al pasar el ratón
            const fechaFormateada = dateFns.format(fechaJS, "EEEE d 'de' MMM 'del' yyyy, 'a las' HH:mm:ss", { locale: dateFns.locale.es });
            $(this).next('.fecha-formateada').text(fechaFormateada).show();
        });

        this.addEventListener('mouseout', function () {
            $(this).next('.fecha-formateada').hide();
        });
    });

    // Obtener la fecha de publicacion de los posteos
    $('.fecha').each(function() {
        var fecha = $(this).text();
        const fechaJS = dateFns.parse(fecha, "dd-MM-yyyy HH:mm:ss", new Date());
        const fechaRelativa = dateFns.formatDistanceToNow(fechaJS, { locale: dateFns.locale.es, addSufix: true });
        $(this).text('hace ' + fechaRelativa);
    });
});