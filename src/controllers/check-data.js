$(document).ready(function () {
    $('#login').on('submit', function(event) {
        event.preventDefault();
        var datosEnviados = {
            'user'      :   $('#user').val(),
            'password'  :   $('#password').val()
        };

        if ($('#user').val() == "" || $('#password').val() == "") {
            $('#login-result').html('Faltan campos por llenar');
            $('#login-result').show();
            setTimeout("$('#login-result').html('')", 5000);
        } else {
            $.ajax({
                url         :   '../models/data.php',
                type        :   'POST',
                data        :   datosEnviados,
                dataType    :   'text',
                success:    function(res) {
                    if (res == 1) {
                        $('#login-result').html('Usuario o clave incorrecto');
                        $('#login-result').show();
                        setTimeout("$('#login-result').html('')", 5000);
                        $('#user').val('');
                        $('#password').val('');
                    } else {
                        location.href = "dashboard.php";
                    }
                }
            });
        }
    });
});