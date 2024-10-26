// Función para obtener el número de notificaciones no leídas
function actualizarContadorNotificaciones() {
    $.ajax({
        url     :   '../models/notifications.php',
        method  :   'POST',
        data    :   { 
                action: 'obtener_no_leidos' // Parámetro para identificar qué acción ejecutar
        },
        success: function(response) {
            const data = JSON.parse(response);
            const unreadCount = data.unread_count;
            const id = data.usuario_id;

            const badge = $('#notification-badge');

            // Si hay notificaciones no leídas, mostrar icono
            if (unreadCount > 0) {
                badge.text(unreadCount);
                badge.show();
            } else {
                badge.hide(); // Ocultar icono si no hay notificaciones no leídas
            }
        },
        error: function() {
            console.error("No se pudo obtener el número de notificaciones no leídas.");
        }
    });
}

// Función para obtener las notificaciones no leídas
function actualizarListadoNotificaciones() {
    $.ajax({
        url: '../models/notifications.php', // El archivo PHP que manejará la solicitud
        method: 'POST',
        data: {
            action: 'obtener_notificaciones' // Parámetro para identificar la acción
        },
        success: function(response) {
            console.log(response);
            $('#notification-list').html(response); // Actualizamos el contenido del listado
        },
        error: function() {
            console.error("No se pudieron cargar las notificaciones.");
        }
    });
}

$('#notification-icon').on('click', function () {
    actualizarListadoNotificaciones();
});

// Llamar a la función al cargar la página
$(document).ready(function() {
    actualizarListadoNotificaciones();
    actualizarContadorNotificaciones();
    // Actualizar cada 10 segundos, tanto el contador, como el listado
    setInterval(actualizarContadorNotificaciones, 10000);
    setInterval(actualizarListadoNotificaciones, 10000);
});