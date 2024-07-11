$(document).ready(function () {
    $('#form-comment').on('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url         :   'new-comment.php',
            type        :   'POST',
            data        :   formData,
            contentType :   false,
            processData :   false,
            success     :   function(response) {
                try {
                    var res = JSON.parse(response);
                } catch (e) {
                    if (response == 'error') {
                        alert('Error al insertar item');
                    } else {
                        var autorComentario = formData.get('autor-comentario');
                        var fechaComentario = formData.get('comment-time');
                        var commentInput = formData.get('comment-input');
                        const fileInput = formData.get('file-input');
                        
                        // Crear el contenido HTML del comentario
                        var commentHTML = '<div class="comment-card-top">' + '<h3>' + autorComentario + '</h3>' + '<div>' + fechaComentario  + '</div>' + '</div>' + '<div>' + commentInput + '</div>';
                    
                        // Verificar si hay un archivo y agregar la imagen al HTML del comentario
                        if (fileInput && fileInput.size > 0) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                commentHTML += '<img src="' + e.target.result + '" alt="Comment image">';
                                appendComment(commentHTML);
                            };
                            reader.readAsDataURL(fileInput);
                        } else {
                            appendComment(commentHTML);
                        }
                        
                        function appendComment(htmlContent) {
                            var comment = $('<div></div>').addClass('post-card comment').html(htmlContent);
                            $('#post-card').after(comment);
                            $('#comment-input').val(''); // Limpiar el campo de entrada del comentario
                        }
                    }                    
                }
            },
            error       :   function (jqXHR, textStatus, errorThrown) {
                alert("Error al insertar item:" + textStatus, jqXHR, errorThrown);
                console.log("Error al insertar item:" + textStatus, jqXHR, errorThrown);
            }
        });
    });
});