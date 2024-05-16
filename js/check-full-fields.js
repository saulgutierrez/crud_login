$(document).ready(function () {
    $('#show-message').on("click", function () {
        var user = document.newUserForm.user.value;
        var password = document.newUserForm.password.value;

        if (user == "" || password == "") {
            $('#message').show();
            setTimeout(function () {
                $('#message').hide();
            }, 5000);
            event.preventDefault();
        }
    });
})