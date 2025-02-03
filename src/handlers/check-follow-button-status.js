$(document).ready(function () {

    function getButtonState() {
        $.ajax({
            url: '../models/get-button-state.php',
            method: 'GET',
            data: { 'id': $('.follow-profile-btn-list').data('id') },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'following') {
                    $('.follow-text-profile-btn-list').text('Siguiendo');
                } else {
                    $('.follow-text-profile-btn-list').text('Seguir');
                }
            },
            error: function(error) {
                console.log("Error recuperando el estado del boton:", error);
            }
        });
    }

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