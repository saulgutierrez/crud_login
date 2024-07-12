$(document).ready(function () {
    $('.unfollow-profile-btn').click(function (event) {
        event.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url     :   'unfollow-user.php',
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