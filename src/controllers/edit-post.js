$(document).ready(function () {
    $('#editPostForm').on('submit', function(event) {
        event.preventDefault(); // Prevenir el envío del formulario tradicional

        var formData = new FormData(this); // Crear un objeto FormData con los datos del formulario

        // Validación de campos
        if ($('#post_title').val() === "" || $('#post_content').val() === "") {
            $('#edit-post-result').html('Faltan campos por llenar');
            $('#edit-post-result').show();
            setTimeout(function() {
                $('#edit-post-result').html('');
            }, 5000);
        } else {
            // Envío de la solicitud AJAX
            $.ajax({
                url: '../models/edit-post-data.php', // URL del script PHP
                type: 'POST',  // Método de la solicitud
                data: formData, // Datos del formulario
                contentType: false, // No establecer tipo de contenido
                processData: false, // No procesar los datos
                success: function(response) {
                    try {
                        // Intentar parsear la respuesta como JSON
                        var res = JSON.parse(response);
                        // Mostrar mensaje de respuesta
                        $('#edit-post-result').html(res.message);
                        $('#edit-post-result').show();
                        setTimeout(function() {
                            $('#edit-post-result').html('');
                        }, 5000);
                    } catch (e) {
                        window.location = 'dashboard.php';
                        setTimeout(function() {
                            $('#edit-post-result').html('');
                        }, 5000);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Manejo de errores de la solicitud AJAX
                    $('#edit-post-result').html('Error en la solicitud: ' + textStatus);
                    $('#edit-post-result').show();
                    setTimeout(function() {
                        $('#edit-post-result').html('');
                    }, 5000);
                }
            });
        }
    });
});
