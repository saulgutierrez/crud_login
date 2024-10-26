$(document).ready(function () {
    $('#newUserForm').on('submit', function(event) {
        event.preventDefault();
        var datosEnviados = {
            'user'      :       $('#user').val(),
            'password'  :       $('#password').val()
        };

        if ($('#user').val() == "" || $('#password').val() == "") {
            $('#message').html('Faltan campos por llenar');
            $('#message').show();
            setTimeout("$('#message').html('')", 5000);
        } else {
            $.ajax({
                url         :   '../models/new-user.php',
                type        :   'POST',
                data        :   datosEnviados,
                dataType    :   'text',
                success     :   function(res) {
                    if (res == 1) {
                        $('#message').html('Seleccione otro nombre de usuario');
                        $('#message').show();
                        setTimeout("$('#message').html('')", 5000);
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