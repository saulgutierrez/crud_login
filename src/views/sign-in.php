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
    <title>Registrarse | GamesDB</title>
</head>
<body>
    <?php include "../views/includes/navbar-forum.php"; ?>
    <form method="POST" id="newUserForm">
        <img src="../../public/svg/user-fill.svg" alt="" class="user-icon">
        <img src="../../public/svg/lock-fill.svg" alt="" class="pass-icon">
        <label for="user">Usuario:</label>
        <input type="text" id="user" name="user">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password">
        <div class="group-buttons">
            <div class="submit-container" onclick="document.getElementById('show-message').click()">
                <div class="imgBox">
                    <img src="../../public/svg/new-user.svg" alt="" class="submit-icon">
                </div>
                <input type="submit" value="Registrarse" id="show-message">
            </div>
            <a href="login.php">
                <div class="imgBox">
                    <img src="../../public/svg/arrow-back.svg" alt="">
                </div>
                <div>Atrás</div>
            </a>
        </div>
        <div id="message" class="message"></div>
    </form>
    <script src="../helpers/sign-in.js"></script>
</body>
</html>