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
        <input type="text" id="userOrEmail" placeholder="Nombre de usuario o correo">
        <input type="text" id="recoveryCode" placeholder="Codigo de recuperacion">
        <input type="password" id="newPassword" placeholder="Nueva contraseña">
        <div class="group-buttons">
            <button type="submit">Confirmar</button>
            <a href="login.php">Atrás</a>
        </div>
        <div id="message" class="message"></div>
    </form>
</body>
</html>