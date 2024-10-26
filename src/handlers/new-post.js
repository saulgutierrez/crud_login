$(document).ready(function () {
    $('#newPostForm').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        formData.append('id_user', $('#id_user').val());
        formData.append('user', $('#user').val());
        formData.append('post_title', $('#post_title').val());
        formData.append('post_content', $('#post_content').val());
        formData.append('post_time', $('#post_time').val());
        formData.append('category', $('#category').val());

        if ($('#post_title').val() == "" || $('#post_content').val() == "") {
            $('#new-post-result').html('Faltan campos por llenar');
            $('#new-post-result').show();
            setTimeout("$('#new-post-result').html('')", 5000);
        } else {
            $.ajax({
                url         :   '../models/new-post.php',
                type        :   'POST',
                data        :   formData,
                contentType :   false,
                processData :   false,
                success:    function(response) {
                    var res = JSON.parse(response);
                    if (res.file_error) {
                        $('#new-post-result').html(res.message);
                        $('#new-post-result').show();
                        setTimeout("$('#new-post-result').html('')", 5000);
                    } else {
                        location.href = "dashboard.php";
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#new-post-result').html('Error en la solicitud: ' + textStatus);
                }
            });
        }
    });
});