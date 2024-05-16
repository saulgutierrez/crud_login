<?php
    require('conexion.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sign-in.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/check-full-fields.js"></script>
    <title>Registrate</title>
</head>
<body>
    <form method="POST" id="newUserForm">
        <label for="user">Usuario</label>
        <input type="text" id="user" name="user">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password">
        <input type="submit" value="Registrarte" id="show-message">
        <div id="message" class="message"></div>
    </form>
</body>
</html>