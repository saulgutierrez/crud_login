$(document).ready(function () {

    $('.like-button').each(function() {
        var id = $(this).data('id');
        getLikeCommentButtonState(this, id);
    });

    $('.like-button').on('click', function(event) {
        var id = $(this).data('id');
        toggleLike(this, id);
    });

    function toggleLike(button, id) {   
        var isLiked = $(button).hasClass('liked-btn');
        $.ajax({
            type    :   "POST",
            url     :   "../models/toggle-like-comment.php",
            data    :   { id : id, action : isLiked ? 'unlike' : 'like' },
            success :   function(response) {
                if (isLiked) {
                    $(button).text('Like').removeClass('liked-btn');
                } else {
                    $(button).text('Liked').addClass('liked-btn');
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
                    $(button).text('Liked').addClass('liked-btn');
                } else {
                    $(button).text('Like').removeClass('liked-btn');
                }
            },
            error : function (error) {
                console.log('Error recuperando el estado del boton', error);
            }
        });
    }
});