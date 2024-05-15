<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/jquery-3.7.1.min.js"></script>
    <title>Login y CRUD</title>
</head>
<body>
    <form id="login" method="POST">
        <img src="svg/key.svg" alt="" class="key-icon">
        <img src="svg/user.svg" alt="" class="user-icon">
        <label for="user">Usuario: </label>
        <input type="text" id="user" name="user"><br>
        <label for="password">Contraseña: </label>
        <input type="password" id="password" name="password"><br>
        <div class="group-buttons">
            <button value="Iniciar sesion">Iniciar sesión</button>
            <a href="php/sign-in.php">Registrate</a>
        </div>
        <div id="login-result" class="login-result"></div>
    </form>

    <script>
        $(document).ready(function () {
            $('#login').on('submit', function(event) {
                event.preventDefault();
                var datosEnviados = {
                    'user'      :   $('#user').val(),
                    'password'  :   $('#password').val()
                };
                $.ajax({
                    url         :   'php/data.php',
                    type        :   'POST',
                    data        :   datosEnviados,
                    dataType    :   'text',
                    success:    function(res) {
                        if (res == 1) {
                            $('#login-result').html('Usuario o clave incorrecto');
                            $('#login-result').show();
                            setTimeout("$('#login-result').html('')", 5000);
                            $('#user').val('');
                            $('#password').val('');
                        } else {
                            location.href = "php/dashboard.php";
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>