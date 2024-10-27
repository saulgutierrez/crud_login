$(document).ready(function() {

    let popupTimeout;

    $(document).on('mouseenter', 'h2 a', function() {
        // Obtén el ID del usuario desde el atributo data-id del enlace
        let userId = $(this).data('id');

        // Selecciona el contenedor info-popup relacionado al post actual
        let popup = $(this).closest('.post-card-top').find('.info-popup');
        
        // Verifica si existe el popup, si no, lo creas
        if (popup.length === 0) {
            popup = $('<div class="info-popup"></div>'); // Crear el popup si no existe
            $(this).closest('.post-card-top').append(popup); // Añadir el popup al contenedor
        }

        // Hacer la llamada AJAX para obtener seguidores y seguidos
        $.ajax({
            url: '../models/get-follow-info.php', // Archivo PHP que recupera la información de seguidores y seguidos
            type: 'POST',
            data: { id: userId },
            success: function(response) {
                // Parsear la respuesta JSON
                let data = JSON.parse(response);

                let userId = $(this).data('id');
                let autor = $(this).data('autor');
                let fotoPerfil = $(this).data('foto');

                // Agregar la información al popup sin reemplazar el contenido anterior
                popup.empty(); // Vaciar el contenido antes de agregar nueva información
                popup.append('<div class="imgBoxProfileImage imgProfilePreview"><img src="' + fotoPerfil + '" alt="Foto de perfil"></div>');
                let profileLink = '';
                if (userId == authUserId) {
                    profileLink = 'profile.php?user=' + autor;
                } else {
                    profileLink = 'profile.php?id=' + userId;
                }
                popup.append('<a class="autorProfilePreview" href="'+ profileLink +'">' + autor + '</a>');
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
                        <button class="follow-btn" data-id="${userId}">
                            ${followButtonText}
                        </button>
                    `;
                    popup.append(followButtonHTML);
                    }
                });

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

    }).on('mouseleave', 'h2 a', function() {
        // Esconde el popup tras un pequeño retraso, por si el cursor entra en el popup
        popupTimeout = setTimeout(function() {
            let popup = $('.info-popup');
            popup.hide();
        }, 200); // Puedes ajustar el tiempo si lo deseas
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
    $(document).on('click', '.follow-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
    
        // Referencia del botón
        let followButton = $(this);
    
        // Preparar los datos que se enviarán
        var datosEnviados = {
            'id': followButton.data('id')
        };
    
        let btnText = followButton.text().trim();
    
        if (btnText === 'Seguir') {
            datosEnviados['action'] = 'follow';
        } else if (btnText === 'Dejar de seguir') {
            datosEnviados['action'] = 'unfollow';
        }
    
        // Llamada AJAX para cambiar el estado de "Seguir/Siguiendo"
        $.ajax({
            url: '../models/toggle-follow.php', // Archivo PHP para cambiar el estado
            type: 'POST',
            data: datosEnviados,
            success: function(response) {   
                try {
                    let newText;
                    if (response.status === 'followed') {
                        // Cambiar el texto a "Siguiendo"
                        newText = 'Siguiendo';
                    } else if (response.status === 'unfollowed') {
                        // Cambiar el texto a "Seguir"
                        newText = 'Seguir';
                    } else {
                        alert('Error: ' + jsonResponse.message);
                    }

                    let newButtonHTML = `
                        <button class="follow-btn" data-id="${followButton.data('id')}">
                            ${newText}
                        </button>
                    `;

                    followButton.replaceWith(newButtonHTML);
                    console.log(newButtonHTML);
                } catch (e) {
                    console.log('Error parsing JSON response: ', e);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error: ', status, error);
            }
        });
    });
    

    $(document).on('mouseover', '.follow-btn', function (e) {
        let followText = $(this).text().trim();

        if (followText === "Siguiendo") {
            $(this).text('Dejar de seguir');
        }
    });
    
    $(document).on('mouseout', '.follow-btn', function (e) {
        if ($(this).text() === "Dejar de seguir") {
            $(this).text('Siguiendo');
        }
    });    
});