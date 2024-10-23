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

            // Si hay notificaciones no leídas, mostrar el distintivo
            if (unreadCount > 0) {
                badge.text(unreadCount);
                badge.show();
            } else {
                badge.hide(); // Ocultar el distintivo si no hay notificaciones no leídas
            }
        },
        error: function() {
            console.error("No se pudo obtener el número de notificaciones no leídas.");
        }
    });
}

// Llamar a la función al cargar la página
$(document).ready(function() {
    actualizarContadorNotificaciones();
    // Actualizar en tiempo real
    setInterval(actualizarContadorNotificaciones, 10000); // Cada 10 segundos
});