$(document).ready(function () {
    $('#newPostForm').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        formData.append('id_user', $('#id_user').val());
        formData.append('user', $('#user').val());
        formData.append('post_title', $('#post_title').val());
        formData.append('post_content', $('#post_content').val());
        
        $.ajax({
            url         :   'post-data.php',
            type        :   'POST',
            data        :   formData,
            contentType :   false,
            processData :   false,
            success:    function(response) {
                var res = JSON.parse(response);
                $('#new-post-result').html(res.message);
                if (res.file_error) {
                    $('#new-post-result').html('Codigo de error de archivo: ' + res.file_error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#new-post-result').html('Error en la solicitud: ' + textStatus);
            }
        });
    });
});