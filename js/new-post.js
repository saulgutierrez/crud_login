$(document).ready(function () {
    $('#newPostForm').on('submit', function(event) {
        event.preventDefault();
        var datosEnviados = {
            'id_user'       :   $('#id_user').val(),
            'user'          :   $('#user').val(),
            'post_title'    :   $('#post_title').val(),
            'post_content'  :   $('#post_content').val()
        };

        if ($('#id_user').val() == "" || $('#post_title').val() == "" || $('#post_content').val() == "") {
            $('#new-post-result').html('Faltan campos por llenar');
            $('#new-post-result').show();
            setTimeout("$('#new-post-result').html('')", 5000);
        } else {
            $.ajax({
                url         :   'post-data.php',
                type        :   'POST',
                data        :   datosEnviados,
                dataType    :   'text',
                success:    function(res) {
                    if (res == 1) {
                        $('#new-post-result').html('Error de conexión con el servidor, inténtelo nuevamente.');
                        $('#login-result').show();
                        setTimeout("$('#new-post-result').html('')", 5000);
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