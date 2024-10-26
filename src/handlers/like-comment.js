$(document).ready(function () {

    $('.like-button-comment').each(function() {
        var id = $(this).data('id');
        getLikeCommentButtonState(this, id);
    });

    $('.like-button-comment').on('click', function(event) {
        var id = $(this).data('id');
        toggleLike(this, id);
    });

     // Evento para mostrar los likes cuando se hace click en el contador
     $('.like-count-comment').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var id_comentario = $(this).data('id');

        $.ajax({
            type    :   "POST",
            url     :   "../models/load-likes-comments.php",
            data    :   { id_comentario: id_comentario },
            success :   function(response) {
                var likesList = $('#likesList');
                likesList.empty();

                try {
                    var users = JSON.parse(response);
                    if (Array.isArray(users)) {
                        users.forEach(function(user) {
                            var userLink;
                            if (user.id == authUserId) {
                                userLink = '<div class="imgBox">' + '<img src='+user.fotografia+'>' + '</div>' + '<a class="liked-usernames" href="profile.php?user='+user.usuario+'">'+ user.usuario +'</a>';
                            } else {
                                userLink = '<div class="imgBox">' + '<img src='+user.fotografia+'>' + '</div>' + '<a class="liked-usernames" href="profile.php?id=' + user.id + '">' + user.usuario + '</a>';
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

    function toggleLike(button, id) {   
        var isLiked = $(button).hasClass('liked-btn-comment');
        $.ajax({
            type    :   "POST",
            url     :   "../models/toggle-like-comment.php",
            data    :   { id : id, action : isLiked ? 'unlike' : 'like' },
            success :   function(response) {
                if (isLiked) {
                    $(button).text('Like').removeClass('liked-btn-comment');
                } else {
                    $(button).text('Liked').addClass('liked-btn-comment');
                }
            },  
            error   :   function(xhr, status, error) {
                alert(error);
            }
        });
    }

    function getLikeCommentButtonState(button, id) {
        $.ajax({
            url         :   '../models/get-like-comment-button-state.php',
            method      :   'GET',
            data        :   { id : id },
            dataType    :   'json',
            success     :   function (response) {
                if (response.status === 'liked') {
                    $(button).text('Liked').addClass('liked-btn-comment');
                } else {
                    $(button).text('Like').removeClass('liked-btn-comment');
                }
            },
            error : function (error) {
                console.log('Error recuperando el estado del boton', error);
            }
        });
    }
});