$(document).ready(function () {
    $('#form-comment').on('submit', function (event) {
        event.preventDefault();
        var datosEnviados = {
            'id-post'           :   $('#id-post').val(),
            'id-autor-post'     :   $('#id-autor-post').val(),
            'autor-comentario'  :   $('#autor-comentario').val(),
            'comment-input'     :   $('#comment-input').val()
        };
        $.ajax({
            url         :   'new-comment.php',
            type        :   'POST',
            data        :   datosEnviados,
            dataType    :   'text',
            success     :   function(res) {
                alert(res);
            }
        });
    });
});