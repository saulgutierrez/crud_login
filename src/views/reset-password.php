<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../controllers/reset-password.js"></script>
    <link rel="stylesheet" href="../../public/css/reset-password.css">
    <title>Restablecer contraseña | GamesDB</title>
</head>
<body>
    <?php include "../views/includes/navbar-forum.php"; ?>
    <form id="recoveryForm">
        <img src="../../public/svg/user-fill.svg" alt="" class="user-icon">
        <img src="../../public/svg/lock-fill.svg" alt="" class="pass-icon">
        <img src="../../public/svg/password-input.svg" alt="" class="code-icon">
        <label for="userOrEmail">Usuario o Email</label>
        <input type="text" id="userOrEmail">
        <label for="recoveryCode">Código de recuperación</label>
        <input type="text" id="recoveryCode">
        <label for="newPassword">Nueva Contraseña</label>
        <input type="password" id="newPassword">
        <div class="group-buttons">
            <button type="submit">
                <div class="imgBox">
                    <img src="../../public/svg/confirm.svg" alt="">
                </div>
                <div>Confirmar</div>
            </button>
            <a href="login.php">
                <div class="imgBox">
                    <img src="../../public/svg/arrow-back.svg" alt="">
                </div>
                Atrás
            </a>
        </div>
        <div id="message" class="message"></div>
    </form>
</body>
</html>