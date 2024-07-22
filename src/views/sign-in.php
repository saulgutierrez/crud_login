<?php
    require('../../config/connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/sign-in.css">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../controllers/check-full-fields.js"></script>
    <title>Registrate</title>
</head>
<body>
    <?php include "../views/includes/navbar.php" ?>
    <form method="POST" id="newUserForm">
        <img src="../../public/svg/user-fill.svg" alt="" class="user-icon">
        <img src="../../public/svg/lock-fill.svg" alt="" class="pass-icon">
        <label for="user">Usuario</label>
        <input type="text" id="user" name="user">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password">
        <div class="group-buttons">
            <input type="submit" value="Registrarte" id="show-message">
            <a href="login.php">Atrás</a>
        </div>
        <div id="message" class="message"></div>
    </form>
</body>
</html>