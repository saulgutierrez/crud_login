$(document).ready(function() {
    $('.delete-post-btn').click(function(event) {
        event.preventDefault();
        var id = $(this).data('id');
        var row = $(this).closest('.post-card');

        $.ajax({
            url     :   '../models/delete-post.php',
            type    :   'POST',
            data    :   { id: id },
            success :   function () {
                row.remove();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error en la solicitud: ' + textStatus);
            }
        });
    });
});