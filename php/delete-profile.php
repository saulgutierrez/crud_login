<?php
    require('conexion.php');
    require('data.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    } else {
        header('Location: dashboard.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/delete-profile.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/check-delete-password.js"></script>
    <title>Confirmar acción</title>
</head>

<body>
    <form method="POST" id="deleteProfileForm">
        <label for="confirm-delete" class="delete-alert">Como medida de seguridad, para confirmar la eliminación de su cuenta, debe ingresar su contraseña.</label>
        <input type="hidden" id="user" name="user" value="<?php echo $user; ?>">
        <input type="password" id="confirm-delete" name="confirm-delete">
        <div class="delete-result" id="delete-result"></div>
        <div class="group-buttons">
            <a href="profile.php?user=<?php echo $user;?>">Regresar</a>
            <button value="Confirmar">Confirmar</button>
        </div>
        <label class="delete-alert">Al eliminar su cuenta, todos sus posts, comentarios y datos personales se eliminarán de nuestros servidores en un plazo no mayor a 72 horas.</label>
    </form>
</body>
</html>

