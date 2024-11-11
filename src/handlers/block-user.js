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
                // Ocultar elementos si el usuario está bloqueado
                document.getElementById('btn-3').style.display = 'none';
                let info = document.querySelector('.info');
                let posts = document.querySelector('.posts');
                let comments = document.querySelector('.comments');
                let followers = document.querySelector('.followers');
                let following = document.querySelector('.following');
                let likes = document.querySelector('.likes');
                let photos = document.querySelector('.photos');

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

                document.getElementById('info-perfil').style.display = 'none';
                document.getElementById('post-content').style.display = 'none';
                document.getElementById('comment-content').style.display = 'none';
                document.getElementById('follower-content').style.display = 'none';
                document.getElementById('following-content').style.display = 'none';
                document.getElementById('likes-content').style.display = 'none';
                document.getElementById('photos-content').style.display = 'none';
            } else {
                document.getElementById('btn-3').style = 'flex';
                // let info = document.querySelector('.info');
                // let posts = document.querySelector('.posts');

                // Obtenemos el color de fondo actual de los botones
                // let infoBgColor = window.getComputedStyle(info).backgroundColor;
                // let postsBgColor = window.getComputedStyle(posts).backgroundColor;

                // if (infoBgColor === "rgb(0, 104, 255)") {
                //     alert('Estamos en la pagina info');
                // } else if (postsBgColor === "rgb(0, 104, 255)") {
                //     alert('Estamos en la página posts');
                // }
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    // Ejecutar la función una vez al cargar la página
    //fetchUserBlockedStatus();
    
});