$(document).ready(function () {
    $('#editProfileForm').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '../models/edit-profile.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Response:', response);

                if (response.status === 'error') {
                    // Mostrar mensaje de error si el nombre de usuario ya existe
                    $('#edit-result').html(response.message);
                    $('#edit-result').show();
                    $('#user').val('');
                    setTimeout(function() {
                        $('#edit-result').html('');
                    }, 5000);
                } else if (response.status === 'success') {
                    $('#edit-result').html(response.message);
                    $('#edit-result').show();
                    setTimeout(function() {
                        window.location = 'dashboard.php';
                    }, 2000);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#edit-result').html('Error en la solicitud: ' + textStatus);
                $('#edit-result').show();
                setTimeout(function () {
                    $('#edit-result').html('');
                }, 5000);
            }
        });
    });
});