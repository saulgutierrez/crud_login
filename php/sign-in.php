<?php
    require('conexion.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/sign-in.css">
    <title>Registrate</title>
</head>
<body>
    <form action="new-user.php" method="POST">
        <label for="new-user">Usuario</label>
        <input type="text" id="new-user" name="new-user">
        <label for="new-password">Contraseña</label>
        <input type="password" id="new-password" name="new-password">
        <input type="submit" value="Registrarte">
    </form>
</body>
</html>