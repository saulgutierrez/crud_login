$(document).ready(function () {
    $('#form-comment').on('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(this);
        var commentInput = formData.get('comment-input');

        if (commentInput.length > 0) {
            $.ajax({
                url         :   '../models/new-comment.php',
                type        :   'POST',
                data        :   formData,
                contentType :   false,
                processData :   false,
                success     :   function(response) {
                    console.log(response);
                    // Evaluamos si la información se proceso correctamente
                    if (typeof response === 'object') {
                        var res = response;
                    } else {
                        try {
                            var res = JSON.parse(response);
                        } catch (e) {
                            console.log("Error en la respuesta JSON: ", response);
                            alert('Error al insertar item');
                            return;
                        }
                    }

                    // Datos retornados por el servidor
                    var autorComentario = res.autorComentario;
                    var fechaComentario = res.fechaComentario;
                    var comentario = res.comentario;
                    var fotografiaAutor = res.imagenAutor;  // Imagen del autor
                    var fileInput = res.imagenSubida;

                    // Crear el contenido HTML del comentario, incluyendo la imagen del autor
                    var commentHTML = 
                    '<div class="comment-card-top">' +
                        '<h2>' +
                            '<div class="imgBoxProfileImage">' +
                                '<img src="' + fotografiaAutor + '" alt="">' +  // Añadir la imagen del autor
                            '</div>' +
                            '<a href="profile.php?user='+ autorComentario +'" class="comment-user">' + autorComentario + '</a>' +
                        '</h2>' +
                        '<div>' + fechaComentario  + '</div>' +
                    '</div>' + 
                    '<div class="comment-card-body">' +
                        '<div>' + comentario + '</div>' +
                        '<a class="like-button">Like</a>' +
                    '</div>';

                // Verificar si se ha subido una imagen
                if (fileInput) {
                    commentHTML += '<img src="' + fileInput + '" alt="Imagen adjunta">';
                }

                // Añadir el comentario al contenedor dinámicamente
                appendComment(commentHTML);


                    appendComment(commentHTML);
                },
                error       :   function (jqXHR, textStatus, errorThrown) {
                    alert("Error al insertar item:" + textStatus, jqXHR, errorThrown);
                    console.log("Error al insertar item:" + textStatus, jqXHR, errorThrown);
                }
            });
        }

        // Función para añadir el comentario dinámicamente al DOM
        function appendComment(htmlContent) {
            var comment = $('<div></div>').addClass('post-card comment').html(htmlContent);
            $('#post-card').after(comment);
            $('#comment-input').val(''); // Limpiar el campo de entrada del comentario
        }
    });
});