<?php
    require('connection.php');
    require('data.php');

    # Si no existe varible de sesion, quiere decir que el usuario no se ha autenticado
    # Negamos el acceso
    if (!isset($_SESSION['user'])) {
         header('Location: ../index.php');
         exit();
     }
     # Si existe, tomamos su nombre de usuario
    $username = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
</head>
<header>
    <h1><a href="../php/dashboard.php">Forum</a></h1>
    <input type="text">
    <!-- Identificamos al usuario dentro de la interfaz -->
    <h2 class="identifier" id="identifier"><?php echo $username; ?></h2>
    <div class="square"></div>
    <div class="dropdown">
        <a href="profile.php?user=<?php echo $username; ?>">Ver perfil</a>
        <a href="../php/logout.php">Cerrar sesion</a>
    </div>
    <h2 class="new-post"><a href="new-post.php?user=<?php echo $username; ?>">Nueva</a></h2>
</header>
</html>
<script src="../js/header.js"></script>