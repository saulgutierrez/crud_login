$(document).ready(function () {
    const { parse, format } = dateFns;
    var fecha = $('.fecha-comment');
    fecha.each(function () {
        this.addEventListener('mouseover', function () {
            // Reemplazar a.m. y p.m. por AM y PM para que la fecha sea válida
            var fecha = $(this).data('fecha');
            const fechaLimpia = fecha.replace("a.m.", "AM").replace("p.m.", "PM");

            // Parsear la fecha y mostrarla formateada al pasar el ratón
            const fechaJS = parse(fechaLimpia, "d/M/yyyy, h:m:s a", new Date());
            const fechaFormateada = format(fechaJS, "EEEE, d 'de' MMMM 'de' yyyy, h:mm a", { locale: dateFns.locale.es });
            $(this).next('.fecha-formateada').text(fechaFormateada).show();
        });

        this.addEventListener('mouseout', function () {
            $(this).next('.fecha-formateada').hide();
        });
    });

    // Obtener la fecha de publicacion de los posteos
    $('.fecha-comment').each(function () {
        var fecha = $(this).text();
        // Formatear la fecha para que se ajuste al formato de parse
        const fechaJS = dateFns.parse(fecha, "d/M/yyyy, h:m:s a", new Date());
        // Obtener la fecha relativa
        const fechaRelativa = dateFns.formatDistanceToNow(fechaJS, { locale: dateFns.locale.es, addSuffix: true });
        $(this).text('hace ' + fechaRelativa);
    });
});