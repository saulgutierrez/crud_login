<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Login y CRUD</title>
</head>
<body>
    <form action="php/data.php" method="POST" class="data-handler">
        <img src="svg/key.svg" alt="" class="key-icon">
        <img src="svg/user.svg" alt="" class="user-icon">
        <label for="user">Usuario: </label>
        <input type="text" id="user" name="user"><br>
        <label for="password" id="password" name="password">Contraseña: </label>
        <input type="password" id="password" name="password"><br>
        <input type="submit" value="Iniciar sesion">
    </form>
</body>
</html>