$(document).ready(function () {
    $(document).on('click', '.like-button', function (event) {
        event.preventDefault();
        event.stopPropagation(); // Detener la propagacion del evento
        var id = $(this).data('id');
        saveLike(id);
    });
});

function saveLike(id) {
    $.ajax({
        type    :   "POST",
        url     :   "save-like.php",
        data    :   { id: id },
        success :   function(response) {
        },
        error   :   function(xhr, status, error) {
            alert(error);
        }
    });
}