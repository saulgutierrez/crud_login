// En este fichero manejamos el estado del boton seguir/siguiendo,
// dentro de la lista de siguiendo

$(document).ready(function () {

    function getButtonState() {
        $('.follow-profile-btn-list').each(function() {
            var $btn = $(this); // Botón actual
            var userId = $btn.data('id'); // ID del usuario asociado al botón
    
            $.ajax({
                url: '../models/get-button-state.php',
                method: 'GET',
                data: { 'id': userId }, // Enviar el ID individualmente
                dataType: 'json',
                success: function(response) {
                    var $textElement = $btn.find('.follow-text-profile-btn-list'); // Buscar el texto dentro del botón específico
    
                    if (response.status === 'following') {
                        $textElement.text('Siguiendo');
                    } else {
                        $textElement.text('Seguir');
                    }
                },
                error: function(error) {
                    console.log("Error recuperando el estado del botón:", error);
                }
            });
        });
    }
    
    // Llamar a la función cuando se cargue la página
    getButtonState();
    

    getButtonState();

    $('.follow-profile-btn-list').click(function (event) {
        event.preventDefault();
        
        var $btn = $(this); // Guardamos el boton clickeado
        var $textElement = $btn.find('.follow-text-profile-btn-list'); // Buscamos el texto dentro del botón

        var datosEnviados = {
            'id': $btn.data('id'),
            'action': $textElement.text() === 'Seguir' ? 'follow' : 'unfollow'
        };

        $.ajax({
            url: '../models/toggle-follow.php',
            type: 'POST',
            data: datosEnviados,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'followed') {
                    $textElement.text('Siguiendo');
                } else if (response.status === 'unfollowed') {
                    $textElement.text('Seguir');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ', status, error);
                alert('An error occurred');
            }
        });
    });
});