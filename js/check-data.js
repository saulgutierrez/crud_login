$(document).ready(function () {
    $('#login').on('submit', function(event) {
        event.preventDefault();
        var datosEnviados = {
            'user'      :   $('#user').val(),
            'password'  :   $('#password').val()
        };
        $.ajax({
            url         :   'php/data.php',
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
                    location.href = "php/dashboard.php";
                }
            }
        });
    });
});