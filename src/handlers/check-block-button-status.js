// En este fichero manejamos el estado del boton bloquear/bloqueado,
// dentro de la lista bloqueos de nuestro perfil
// (profile.php?user=)
$(document).ready(function () {
    function getBlockButtonState() {
        $.ajax({
            url         :   '../models/get-block-button-state.php',
            method      :   'GET',
            data        :   { 'id': $('.blocked-profile-btn-list').data('id') },
            dataType    :   'json',
            success     :   function (response) {
                if (response.status === 'blocked') {
                    $('.blocked-text-profile-btn-list').text('Bloqueado');
                } else {
                    $('.blocked-text-profile-btn-list').text('Bloquear');
                }
            },
            error: function(error) {
                console.log("Error recuperando el estado del boton: ", error);
            }
        });
    }

    getBlockButtonState();

    $('.blocked-profile-btn-list').click(function (event) {
        event.preventDefault();

        var $btn = $(this); // Guardamos el boton clickeado
        var $textElement = $btn.find('.blocked-text-profile-btn-list');

        var datosEnviados = {
            'id': $btn.data('id'),
            'action': $textElement.text() === 'Bloquear' ? 'block' : 'unblock'
        };

        $.ajax({
            url         :   '../models/toggle-block.php',
            type        :   'POST',
            data        :   datosEnviados,
            dataType    :   'json',
            success     :   function (response) {
                if (response.status === 'blocked') {
                    $textElement.text('Bloqueado');
                } else if (response.status === 'unblocked') {
                    $textElement.text('Bloquear');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error   :   function (xhr, status, error) {
                console.error('AJAX Error: ', status, error);
            }
        });
    });
});