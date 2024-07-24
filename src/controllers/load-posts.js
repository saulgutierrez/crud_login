$(document).ready(function() {
    function loadPosts() {
        $.ajax({
            url: 'fetch-posts.php',
            method: 'GET',
            success: function(data) {
                $('#registros').html(data);

                $('.post-card').on('click', function() {
                    window.location.href = $(this).data('href');
                });

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
            }
        });
    }

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

    loadPosts();
    setInterval(loadPosts, 60000); // Carga los posts cada minuto
});
