<?php
    require('../../config/connection.php');
    require('../models/data.php');

    # Si no existe varible de sesion, quiere decir que el usuario no se ha autenticado
    # Negamos el acceso
    if (!isset($_SESSION['user'])) {
         header('Location: ../index.php');
         exit();
     }
     # Si existe, tomamos su nombre de usuario
    $username = $_SESSION['user'];
    /**
    * 
    * @var object $conn
    */
    $sqlGetProfilePhoto = "SELECT fotografia FROM usuarios WHERE usuario = '$username'";
    $queryGetProfilePhoto = $conn->query($sqlGetProfilePhoto);

    if ($queryGetProfilePhoto->num_rows > 0) {
        while ($row = $queryGetProfilePhoto->fetch_assoc()) {
            $foto = $row['fotografia'];
            $rutaFotoPorDefecto = "../../public/img/profile-default.svg";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/header.css">
</head>
<header>
    <h1><a href="dashboard.php">Forum</a></h1>
    <input type="text" placeholder="Buscar">
    <!-- Identificamos al usuario dentro de la interfaz -->
     <div class="profile">
        <div class="imgBox">
            <img src="<?php if($foto != '') { echo $foto; } else { echo $rutaFotoPorDefecto; } ?>" alt="">
        </div>
        <h2 class="identifier" id="identifier"><?php echo $username; ?></h2>
    </div>
    <div class="square"></div>
    <div class="dropdown">
        <a href="profile.php?user=<?php echo $username; ?>" class="profile-link">
            <div class="imgBox">
                <img src="../../public/svg/profile.svg" alt="">
            </div>
            <div>Perfil</div>
        </a>
        <a href="../models/logout.php" class="logout">
            <div class="imgBox">
                <img src="../../public/svg/session-leave.svg" alt="">
            </div>
            <div>Cerrar sesión</div>
        </a>
    </div>
    <h2 class="new-post">
        <div class="new-post-logo">
            <a href="new-post.php?user=<?php echo $username; ?>"><img src="../../public/svg/new.svg" alt=""></a>
        </div>
        <a class="new-post-link" href="new-post.php?user=<?php echo $username; ?>">Nuevo</a>
    </h2>
</header>
</html>
<script src="../helpers/header.js"></script>