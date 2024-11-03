$(document).ready(function () {

    function getBlockButtonState() {
        $.ajax({
            url         :   '../models/get-block-button-state.php',
            method      :   'GET',
            data        :   { 'id': $('.block-profile-btn').data('id') },
            dataType    :   'json',
            success     :   function (response) {
                if (response.status === 'blocked') {
                    $('.blocked-text').text('Bloqueado');
                } else {
                    $('.blocked-text').text('Bloquear');
                }
            },
            error: function(error) {
                console.log("Error recuperando el estado del boton: ", error);
            }
        });
    }

    getBlockButtonState();

    $('.block-profile-btn').click(function (event) {
        event.preventDefault();
        var datosEnviados = {
            'id': $(this).data('id')
        };

        var btnText = $('.blocked-text').text();
        datosEnviados['action'] = btnText === 'Bloquear' ? 'block' : 'unblock';

        $.ajax({
            url         :   '../models/toggle-block.php',
            type        :   'POST',
            data        :   datosEnviados,
            dataType    :   'json',
            success     :   function (response) {
                if (response.status === 'blocked') {
                    $('.blocked-text').text('Bloqueado');
                } else if (response.status === 'unblocked') {
                    $('.blocked-text').text('Bloquear');
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