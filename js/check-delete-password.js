$(document).ready(function () {
    $('#deleteProfileForm').on('submit', function(event) {
        event.preventDefault();
        var datosEnviados = {
            'user'              :   $('#user').val(),
            'id_user'           :   $('#id_user').val(),
            'confirm-delete'    :   $('#confirm-delete').val()
        };

        if ($('#confirm-delete').val() == "") {
            $('#delete-result').html('Rellene este campo');
            $('#delete-result').show();
            setTimeout("$('#delete-result').html('')", 5000);
        } else {
            $.ajax({
                url         :   'delete-data.php',
                type        :   'POST',
                data        :   datosEnviados,
                dataType    :   'text',
                success:    function(res) {
                    if (res == 1) {
                        $('#delete-result').html('La clave introducida es incorrecta');
                        $('#delete-result').show();
                        setTimeout("$('#delete-result').html('')", 5000);
                        $('#confirm-delete').val('');
                    } else {
                        location.href = "../index.php";
                    }
                }
            })
        }
    });
});