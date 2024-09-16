$(document).ready(function () {
    $('#recoveryForm').on("submit", function (e) {
        e.preventDefault();
    
        const userOrEmail = $('#userOrEmail').val();
        const recoveryCode = $('#recoveryCode').val();
        const newPassword = $('#newPassword').val();

        if ($('#userOrEmail').val() == "" || $('#recoveryCode').val() == "" || $('#newPassword').val() == "") {
            $('#message').html('Faltan campos por llenar');
            $('#message').show();
            setTimeout("$('#message').html('')", 5000);
        } else {
            $.ajax({
                url     :   '../models/reset-password.php',
                type  :   'POST',
                data    :   {
                    userOrEmail     :   userOrEmail,
                    recoveryCode    :   recoveryCode,
                    newPassword     :   newPassword
                },
                success:    function (response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        console.log(data);
                        alert('Contraseña cambiada exitosamente');
                        window.location.href = "login.php";
                    } else {
                        console.log(data);
                        alert(data.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error en la solicitud: ' + textStatus);
                }
            });
        }
    });
});