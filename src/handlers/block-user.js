$(document).ready(function () {

    function getBlockButtonState() {
        $.ajax({
            url         :   '../models/get-block-button-state.php',
            method      :   'GET',
            data        :   { 'id': $('.block-profile-btn').data('id') },
            dataType    :   'json',
            success     :   function (response) {
                if (response.status === 'blocked') {
                    $('.blocked-text').text('Bloqueado');
                } else {
                    $('.blocked-text').text('Bloquear');
                }
            },
            error: function(error) {
                console.log("Error recuperando el estado del boton: ", error);
            }
        });
    }

    getBlockButtonState();

    $('.block-profile-btn').click(function (event) {
        event.preventDefault();
        var datosEnviados = {
            'id': $(this).data('id')
        };
    
        var btnText = $('.blocked-text').text();
        datosEnviados['action'] = btnText === 'Bloquear' ? 'block' : 'unblock';
    
        $.ajax({
            url         :   '../models/toggle-block.php',
            type        :   'POST',
            data        :   datosEnviados,
            dataType    :   'json',
            success     :   function (response) {
                if (response.status === 'blocked') {
                    $('.blocked-text').text('Bloqueado');
                } else if (response.status === 'unblocked') {
                    $('.blocked-text').text('Bloquear');
                } else {
                    alert('Error: ' + response.message);
                }
    
                // Llamar a fetchUserBlockedStatus() para actualizar el estado de la interfaz
                fetchUserBlockedStatus();
            },
            error   :   function (xhr, status, error) {
                console.error('AJAX Error: ', status, error);
            }
        });
    });
    
    // Configurar la función para verificar el estado de bloqueo dinámicamente
    function fetchUserBlockedStatus() {
        const formData = new FormData();
        formData.append('myProfileId', myProfileId);
        formData.append('otherUserId', otherUserId);
    
        fetch('../models/get-block-state.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.isBlocked) {
                // Ocultar boton de seguimiento si el usuario está bloqueado
                document.getElementById('btn-3').style.display = 'none';
                // Seleccionamos los botones que muestran la informacion, en el menu lateral
                let info = document.querySelector('.info');
                let posts = document.querySelector('.posts');
                let comments = document.querySelector('.comments');
                let followers = document.querySelector('.followers');
                let following = document.querySelector('.following');
                let likes = document.querySelector('.likes');
                let photos = document.querySelector('.photos');

                // Ocultamos todas las pantallas cuando se hace click en cualquier boton
                info.addEventListener('click', function () {
                    document.getElementById('info-perfil').style.display = 'none';
                });

                posts.addEventListener('click', function () {
                    document.getElementById('post-content').style.display = 'none';
                });

                comments.addEventListener('click', function () {
                    document.getElementById('comment-content').style.display = 'none';
                });

                followers.addEventListener('click', function () {
                    document.getElementById('follower-content').style.display = 'none';
                });

                following.addEventListener('click', function () {
                    document.getElementById('following-content').style.display = 'none';
                });

                likes.addEventListener('click', function () {
                    document.getElementById('likes-content').style.display = 'none';
                });

                photos.addEventListener('click', function () {
                    document.getElementById('photos-content').style.display = 'none';
                });

                // Todas las pantallas se ocultan por defecto
                document.getElementById('info-perfil').style.display = 'none';
                document.getElementById('post-content').style.display = 'none';
                document.getElementById('comment-content').style.display = 'none';
                document.getElementById('follower-content').style.display = 'none';
                document.getElementById('following-content').style.display = 'none';
                document.getElementById('likes-content').style.display = 'none';
                document.getElementById('photos-content').style.display = 'none';
            } else {
                // Mostramos el boton seguir
                document.getElementById('btn-3').style = 'flex';
                // Seleccionamos los botones que muestrar la informacion, en el menu lateral
                let info = document.querySelector('.info');
                let posts = document.querySelector('.posts');
                let comments = document.querySelector('.comments');
                let followers = document.querySelector('.followers');
                let following = document.querySelector('.following');
                let likes = document.querySelector('.likes');
                let photos = document.querySelector('.photos');

                // Obtenemos el color de fondo actual de los botones
                let infoBgColor = window.getComputedStyle(info).backgroundColor;
                let postsBgColor = window.getComputedStyle(posts).backgroundColor;
                let commentsBgColor = window.getComputedStyle(comments).backgroundColor;
                let followersBgColor = window.getComputedStyle(followers).backgroundColor;
                let followingBgColor = window.getComputedStyle(following).backgroundColor;
                let likesBgColor = window.getComputedStyle(likes).backgroundColor;
                let photosBgColor = window.getComputedStyle(photos).backgroundColor;

                // Evaluamos el color de fondo actual de cada boton, que indica que esta seleccionado,
                // para mostrar la pantalla de informacion que corresponde (En un primer momento, cuando se
                // desbloquea el usuario)
                if (infoBgColor === "rgb(0, 123, 255)") {
                    document.getElementById('info-perfil').style.display = 'flex';
                } else if (postsBgColor === "rgb(0, 123, 255)") {
                    document.getElementById('post-content').style.display = 'flex';
                } else if (commentsBgColor == "rgb(0, 123, 255)") {
                    document.getElementById('comment-content').style.display = 'flex';
                } else if (followersBgColor == "rgb(0, 123, 255)") {
                    document.getElementById('follower-content').style.display = 'flex';
                } else if (followingBgColor == "rgb(0, 123, 255)") {
                    document.getElementById('following-content').style.display = 'flex';
                } else if (likesBgColor == "rgb(0, 123, 255)") {
                    document.getElementById('likes-content').style.display = 'flex';
                } else if (photosBgColor == "rgb(0, 123, 255)") {
                    document.getElementById('photos-content').style.display = 'flex';
                }

                // Cada que hacemos click en cualquier boton que muestre informacion del usuario, mostramos
                // la pantalla que corresponde
                info.addEventListener('click', function () {
                    document.getElementById('info-perfil').style.display = 'flex';
                });

                posts.addEventListener('click', function () {
                    document.getElementById('post-content').style.display = 'flex';
                });

                comments.addEventListener('click', function () {
                    document.getElementById('comment-content').style.display = 'flex';
                });

                followers.addEventListener('click', function () {
                    document.getElementById('follower-content').style.display = 'flex';
                });

                following.addEventListener('click', function () {
                    document.getElementById('following-content').style.display = 'flex';
                });

                likes.addEventListener('click', function () {
                    document.getElementById('likes-content').style.display = 'flex';
                });

                photos.addEventListener('click', function () {
                    document.getElementById('photos-content').style.display = 'flex';
                });
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    //Ejecutar la función una vez al cargar la página
    fetchUserBlockedStatus();
});