<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/login.css">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../handlers/check-data.js"></script>
    <title>Foro | Games DB</title>
</head>
<body>
    <?php include "../views/includes/navbar-forum.php"; ?>
    <form id="login" method="POST">
        <img src="../../public/svg/user-fill.svg" alt="" class="user-icon">
        <img src="../../public/svg/lock-fill.svg" alt="" class="pass-icon">
        <label for="user">Usuario: </label>
        <input type="text" id="user" name="user"><br>
        <label for="password">Contraseña: </label>
        <input type="password" id="password" name="password"><br>
        <div class="group-buttons">
            <button value="Iniciar sesion">
                <div class="imgBox">
                    <img src="../../public/svg/session-join.svg" alt="">
                </div>
                <div>Iniciar sesión</div>
            </button>
            <a href="sign-in.php">
                <div class="imgBox">
                    <img src="../../public/svg/new-user.svg" alt="">
                </div>
                <div>Registrarse</div>
            </a>
        </div>
        <a href="../views/reset-password.php" class="reset-password-link">¿Olvidaste tu contraseña?</a>
        <div id="login-result" class="login-result"></div>
    </form>
</body>
</html>