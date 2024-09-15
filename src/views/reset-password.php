<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../controllers/reset-password.js"></script>
    <title>Reset Password</title>
</head>
<body>
    <form id="recoveryForm">
        <input type="text" id="userOrEmail" placeholder="Nombre de usuario o correo" required>
        <input type="text" id="recoveryCode" placeholder="Codigo de recuperacion" required>
        <input type="password" id="newPassword" placeholder="Nueva contraseña" required>
        <button type="submit">Recuperar contraseña</button>
    </form>
</body>
</html>