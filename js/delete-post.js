$(document).ready(function() {
    $('.delete-post-btn').click(function(event) {
        event.preventDefault();
        var id = $(this).data('id');
        var row = $(this).closest('.post-card');

        $.ajax({
            url     :   'delete-post.php',
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