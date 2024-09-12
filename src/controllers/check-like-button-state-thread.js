$(document).ready(function () {
    $('.like-button').each(function() {
        var id = $(this).data('id');
        getLikeButtonState(this, id);
    });

    // Evento para el click en los botones de like
    $('.like-button').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var id = $(this).data('id');
        toggleLike(this, id);
    });

    // Evento para mostrar los likes cuando se hace click en el contador
    $('.like-count').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var id_post = $(this).data('id');

        $.ajax({
            type: "POST",
            url: "../models/load-likes.php",
            data: { id_post: id_post },
            success: function(response) {
                var likesList = $('#likesList');
                likesList.empty();

                try {
                    var users = JSON.parse(response);
                    if (Array.isArray(users)) {
                        users.forEach(function(user) {
                            var userLink;
                            if (user.liked_by == authUserId) {
                                userLink = '<div class="imgBox">' + '<img src='+user.fotografia+'>' + '</div>' + '<a class="liked-usernames" href="profile.php?user='+user.usuario+'">'+ user.usuario +'</a>';
                            } else {
                                userLink = '<div class="imgBox">' + '<img src='+user.fotografia+'>' + '</div>' + '<a class="liked-usernames" href="profile.php?id=' + user.liked_by + '">' + user.usuario + '</a>';
                            }
                            likesList.append('<li class="list-group-item">' + userLink + '</li>');
                        });
                    } else {
                        likesList.append('<li class="list-group-item">No likes yet</li>');
                    }
                } catch (e) {
                    likesList.append('<li class="list-group-item">Error loading likes</li>');
                    console.log(e);
                }

                $('#likesModal').modal('show');
            },
            error: function(xhr, status, error) {
                alert('Error loading likes: ' + error);
            }
        });
    });

    // Función para alternar el estado de like
    function toggleLike(button, id) {
        var isLiked = $(button).hasClass('liked-btn');
        $.ajax({
            type: "POST",
            url: "../models/toggle-like.php",
            data: { id: id, action: isLiked ? 'unlike' : 'like' },
            success: function(response) {
                if (isLiked) {
                    $(button).text('Like').removeClass('liked-btn');
                } else {
                    $(button).text('Liked').addClass('liked-btn');
                }
            },
            error: function(xhr, status, error) {
                alert(error);
            }
        });
    }

    // Función para obtener el estado del botón de like
    function getLikeButtonState(button, id) {
        $.ajax({
            url: '../models/get-like-button-state.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'liked') {
                    $(button).text('Liked').addClass('liked-btn');
                } else {
                    $(button).text('Like').removeClass('liked-btn');
                }
            },
            error: function(error) {
                console.log('Error recuperando el estado del botón', error);
            }
        });
    }
});