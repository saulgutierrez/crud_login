$(document).ready(function() {
    $('.delete-comment-btn').click(function(event) {
        event.preventDefault();
        var id = $(this).data('id');
        var row = $(this).closest('.comment-card');

        $.ajax({
            url     :   'delete-comment.php',
            type    :   'POST',
            data    :   { id: id },
            success :   function (response) {
                if (response == 'success') {
                    row.remove();
                } else {
                    alert('Error al eliminar el registro.');
                }
            }
        });
    });
});