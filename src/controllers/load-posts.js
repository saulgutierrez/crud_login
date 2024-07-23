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

                $('.like-button').on('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    var id = $(this).data('id');
                    saveLike(id);
                });
            }
        });
    }

    function saveLike(id) {
        $.ajax({
            type: "POST",
            url: "../models/save-like.php",
            data: { id: id },
            success: function(response) {
                alert('Liked');
            },
            error: function(xhr, status, error) {
                alert(error);
            }
        });
    }

    loadPosts();
    
    setInterval(loadPosts, 60000);
});