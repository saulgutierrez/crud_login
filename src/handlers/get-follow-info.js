$(document).ready(function() {

    let popupTimeout;

    $(document).on('mouseenter', 'h2 a', function() {
        // Obtén el ID del usuario desde el atributo data-id del enlace
        let userId = $(this).data('id');

        // Selecciona el contenedor info-popup relacionado al post actual
        let popup = $(this).closest('.post-card-top-main-content').find('.info-popup');
        
        // Verifica si existe el popup, si no, lo creas
        if (popup.length === 0) {
            popup = $('<div class="info-popup"></div>'); // Crear el popup si no existe
            $(this).closest('.post-card-top-main-content').append(popup); // Añadir el popup al contenedor
        }

        // Hacer la llamada AJAX para obtener seguidores y seguidos
        $.ajax({
            url     :   '../models/get-follow-info.php', // Archivo PHP que recupera la información de seguidores y seguidos
            type    :   'POST',
            data    :   { id: userId },
            success :   function(response) {
                // Parsear la respuesta JSON
                let data = JSON.parse(response);

                let userId = $(this).data('id');
                let autor = $(this).data('autor');
                let fotoPerfil = $(this).data('foto');

                // Agregar la información al popup sin reemplazar el contenido anterior
                popup.empty(); // Vaciar el contenido antes de agregar nueva información
                popup.append('<div class="imgBoxProfileImage imgProfilePreview"><img src="' + fotoPerfil + '" alt="Foto de perfil"></div>');
                popup.append('<a class="autorProfilePreview" href="profile.php?id=' + userId + '">' + autor + '</a>');
                let seguidoresSeguidosHTML = `
                    <div class="follow-info-container">
                        <div class="seguidores">
                            <div>Seguidores</div>
                            <div> ${data.seguidores}</div>
                        </div>
                        <div class="siguiendo">
                            <div>Siguiendo</div>
                            <div> ${data.seguidos}</div>
                        </div>
                        <div class="post_count">
                            <div>Posts</div>
                            <div> ${data.posts_count} </div>
                        </div>
                    </div>
                `;
                popup.append(seguidoresSeguidosHTML);

                // Ajustar la posición del popup cerca del enlace
                popup.css({
                    display: 'block',
                    top: 40, // Mostrarlo 10px debajo del enlace
                    left: 70 // Centrar horizontalmente
                });

                clearTimeout(popupTimeout); // Limpiar cualquier timeout pendiente de ocultar
            }.bind(this), // Necesitas bindear el contexto para acceder a $(this) correctamente dentro del success
            error: function() {
                console.log("Error al recuperar la información de seguidores.");
            }
        });

        // AJAX para obtener el estado "Seguir/Siguiendo"
        $.ajax({
            url:    '../models/get-follow-status.php',
            type:   'POST',
            data:   { id: userId },
            success: function (response) {
                let data = JSON.parse(response);
                let followButtonText = data.status;

                if (followButtonText === 'following') {
                    followButtonText = "Siguiendo";
                } else if (followButtonText === 'not_following') {
                    followButtonText = 'Seguir';
                }

                console.log(followButtonText);

                // Agregar botón "Seguir/Siguiendo" al popup
                let followButtonHTML = `
                    <button class="follow-btn" data-id="${userId}" onclick="event.stopPropagation();">
                        ${followButtonText}
                    </button>
                `;
                popup.append(followButtonHTML);
                }
            });

        popup.css({
            display: 'block',
            top: 40, // Mostrarlo 10px debajo del enlace
            left: 70 // Centrar horizontalmente
        });
    }).on('mouseleave', 'h2 a', function() {
        popupTimeout = setTimeout(function() {
            let popup = $('.info-popup');
            // Verifica si el cursor está sobre el botón de seguir
            if (!popup.is(':hover')) {
                popup.hide();
            }
        }, 500);
    });

    // Ocultar el popup cuando el cursor sale del popup
    $(document).on('mouseleave', '.info-popup', function() {
        popupTimeout = setTimeout(function() {
            $('.info-popup').hide();
        }, 300); // Puedes ajustar el tiempo si lo deseas
    });

    // Mantener el popup visible cuando el cursor está sobre él
    $(document).on('mouseenter', '.info-popup', function() {
        clearTimeout(popupTimeout); // Cancelar el ocultamiento del popup cuando el cursor entra en el popup
    });

    // Al hacer click sobre el boton "Seguir/Siguiendo"
    $('.follow-btn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var datosEnviados = {
            'id'    :   $(this).data('id')
        }
        let btnText = $(this).text();
        if (btnText === 'Seguir') {
            datosEnviados['action'] = 'follow';
        } else if (btnText === 'Dejar de seguir') {
            datosEnviados['action'] = 'unfollow';
        }

        // Llamada AJAX para cambiar el estado de "Seguir/Siguiendo"
        $.ajax({
            url:    '../models/toggle-follow.php', // Archivo PHP para cambiar el estado
            type:   'POST',
            data:   datosEnviados,
            success: function(response) {
                try {
                    var jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'followed') {
                        $(e.target).text('Siguiendo');
                    } else if (jsonResponse.status === 'unfollowed') {
                        $(e.target).text('Seguir');
                    } else {
                        alert('Error: ' + jsonResponse.message);
                    }
                } catch (e) {
                    console.log('Error parsing JSON response: ', e);
                }
            },
            error   :   function (xhr, status, error) {
                console.log('AJAX Error: ', status, error);
            }
        });
    });
});