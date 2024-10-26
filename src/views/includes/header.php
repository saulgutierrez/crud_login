<?php
    require('../../config/connection.php');
    require('../models/session.php');
    require('../models/notifications.php');

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

    # Obtenemos el id del usuario para usarlo como indice al recuperar sus notificaciones
    $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = '$username'";
    $queryGetIdUser = $conn->query($sqlGetIdUser);

    if ($queryGetIdUser->num_rows > 0) {
        while ($rowGetIdUser = $queryGetIdUser->fetch_assoc()) {
            $id = $rowGetIdUser['id'];
        }
    }

    $notificaciones = obtener_notificaciones($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/header.css">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
</head>
<header>
    <h1>
        <a href="dashboard.php">
            <div class="imgBox">
                <img src="../../public/svg/forum.svg" alt="">
            </div>
            <div>Forum</div>
        </a>
    </h1>
    <form id="searchForm" class="searchForm" onsubmit="return false;">
        <input type="text" id="searchQuery" placeholder="Buscar">
        <button type="submit" class="searchBtn">
            <div class="imgBox">
                <img src="../../public/svg/search.svg" alt="">
            </div>
            <div>Buscar</div>
        </button>
    </form>
    <div id="searchResults" class="searchResults"></div>
    <!-- Identificamos al usuario dentro de la interfaz -->
     <div class="profile">
        <div class="imgBox">
            <img src="<?php if ($foto != '') { echo $foto; } else { echo $rutaFotoPorDefecto; } ?>" alt="">
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
        <a href="#" class="recovery" id="generateCodesLink">
            <div class="imgBox">
                <img src="../../public/svg/key.svg" alt="">
            </div>
            <div class="codes">
                <div>Generar códigos de recuperación</div>
                <div class="codesContainer" id="codesContainer"></div>
            </div>
        </a>
    </div>
    
    <div class="notification-icon">
        <img src="../../public/svg/bell-outlined.svg" alt="">
        <span id="notification-badge" class="badge">0</span>
    </div>
    <div class="square-notifications"></div>
    <div class="dropdown-notifications">
        <div id="notification-list">
            <!-- Se recogen las notificaciones de forma dinamica -->
        </div>
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
<script src="../handlers/search-users.js"></script>
<script src="../handlers/generate-recovery-codes.js"></script>
<script src="../handlers/update-notifications.js"></script>