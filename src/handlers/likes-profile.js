$(document).ready(function () {
    $('.like-button').each(function() {
        var id = $(this).data('id');
        getLikeButtonState(this, id);
    });

    $('.like-button').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var id = $(this).data('id');
        toggleLike(this, id);
    });

    // Adjuntar evento a los contadores de likes
    $('.like-count').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var id_post = $(this).data('id');
    
        $.ajax({
            type: "POST",
            url: "../models/load-likes.php",
            data: { id_post: id_post },
            success: function(response) {
                console.log("Response from server:", response);
                var likesList = $('#likesList');
                likesList.empty();
    
                try {
                    var users = JSON.parse(response);
                    console.log("Parsed users:", users);
                    if (Array.isArray(users) && users.length > 0) {
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
                }
    
                // Mostrar el modal
                $('#likeModal').css('display', 'flex');
            },
            error: function(xhr, status, error) {
                alert('Error loading likes: ' + error);
            }
        });
    });
    
    // Cerrar el modal al hacer clic en el botón de cierre
    $('.close-button').on('click', function() {
        $('#likeModal').css('display', 'none');
    });
    
    // Cerrar el modal al hacer clic fuera del contenido
    $(window).on('click', function(event) {
        if ($(event.target).is('#likeModal')) {
            $('#likeModal').css('display', 'none');
        }
    });    

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