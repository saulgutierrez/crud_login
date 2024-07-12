$(document).ready(function () {
    $('.follow-profile-btn').click(function (event) {
        event.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url     :   'follow-user.php',
            type    :   'POST',
            data    :   { id: id },
            success :   function (response) {
                if (response == 'success') {
                    
                } else {
                    alert('Error');
                }
            }
        });
    });
})