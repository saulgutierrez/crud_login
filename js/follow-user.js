$(document).ready(function () {

    function getButtonState() {
        // Función para obtener el estado del botón desde el backend
        $.ajax({
            url         :   'get-button-state.php',
            method      :   'GET',
            data        :   { 'id': $('.follow-profile-btn').data('id') },
            dataType    :   'json',
            success     :   function (response) {
                if (response.status === 'following') {
                    $('.follow-profile-btn').text('Siguiendo');
                } else {
                    $('.follow-profile-btn').text('Seguir');
                }
            },
            error   :   function(error) {
                console.log("Error recuperando el estado del boton:", error);
            }
        });
    }

    // Obtener el estado del boton al cargar la página
    getButtonState();

    // Manejar click en el boton
    $('.follow-profile-btn').click(function (event) {
        event.preventDefault();
        var datosEnviados = {
            'id'        :   $(this).data('id'),
            'btnText'   :   'Siguiendo'
        };
        $.ajax({
            url         :   'follow-user.php',
            type        :   'POST',
            data        :   datosEnviados,
            dataType    :   'text',
            success :   function (response) {
                if (response === 'success') {
                    $('.follow-profile-btn').text('Siguiendo');
                } else {
                    alert('Error: ' + response);
                }
            },
            error   :   function (xhr, status, error) {
                console.error('AJAX Error: ', status, error);
                alert('An error ocurred');
            }
        });
    });
})