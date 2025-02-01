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
        var datosEnviados = {
            'id': $(this).data('id')
        };

        var btnText = $('.follow-text-profile-btn-list').text();
        datosEnviados['action'] = btnText === 'Seguir' ? 'follow' : 'unfollow';

        $.ajax({
            url: '../models/toggle-follow.php',
            type: 'POST',
            data: datosEnviados,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'followed') {
                    $('.follow-text-profile-btn-list').text('Siguiendo');
                } else if (response.status === 'unfollowed') {
                    $('.follow-text-profile-btn-list').text('Seguir');
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