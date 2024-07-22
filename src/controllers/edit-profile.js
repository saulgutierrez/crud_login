$(document).ready(function () {
    $('#editProfileForm').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        // Validacion de campos
        if ($('#user').val() == "" || $('#password').val() == "") {
            $('#edit-result').html('El usuario y contraseña son campos obligatorios');
            $('#edit-result').show();
            setTimeout("$('#edit-result').html('')", 5000);
        } else {
            // Envio de solicitud AJAX
            $.ajax({
                url         :   '../models/edit-data.php',    // URL del script PHP
                type        :   'POST',             // Método de la solicitud
                data        :   formData,           // Datos del formulario
                contentType :   false,              // No establecer tipo de contenido
                processData :   false,              // No procesar los datos
                success:    function(response) {
                    try {
                        // Intentar parsear la respuesta como JSON
                        var res = JSON.parse(response);
                    } catch(e) {
                        if (response == 'error') {
                            $('#edit-result').html('El nombre de usuario ya existe. Seleccione otro.');
                            $('#edit-result').show();
                            $('#user').val('');
                            setTimeout(function () {
                                $('#edit-result').html('');
                            }, 5000);
                        } else {
                            window.location = 'dashboard.php';
                        }
                    }
                },
                error:  function (jqXHR, textStatus, errorThrown) {
                    $('#edit-result').html('Error en la solicitud: ' + textStatus);
                    $('#edit-result').show();
                    setTimeout(function () {
                        $('#edit-result').html();
                    }, 5000);
                }
            });
        }
    });
});