$(document).ready(function () {
    $('#editPostForm').on('submit', function(event) {
        event.preventDefault();
        var datosEnviados = {
            'id_post'       :   $('#id_post').val(),
            'id_user'       :   $('#id_user').val(),
            'user'          :   $('#user').val(),
            'post_title'    :   $('#post_title').val(),
            'post_content'  :   $('#post_content').val()
        };

        if ($('#post_title').val() == "" || $('#post_content').val() == "") {
            $('#edit-post-result').html('Faltan campos por llenar');
            $('#edit-post-result').show();
            setTimeout("$('#edit-post-result').html('')", 5000);
        } else {
            $.ajax({
                url         :   'edit-post-data.php',
                type        :   'POST',
                data        :   datosEnviados,
                dataType    :   'text',
                success:    function(res) {
                    if (res == 0) {
                        location.href = "profile.php";
                    } else  {
                        alert('Error al actualizar');
                    } 
                },
                error:  function (e) {
                    alert('Error');
                }
            });
        }
    });
});