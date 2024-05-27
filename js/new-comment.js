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
            success     :   function() {
                var comment = $('<div></div>').addClass('post-card comment').html('<h3>' + datosEnviados["autor-comentario"] + '</h3>' + '<div>' + datosEnviados["comment-input"] + '</div>');
                $('#main-container').append(comment);
                $('#comment-input').val('');
            },
            error       :   function() {
                alert("Error al insertar item");
            }
        });
    });
});