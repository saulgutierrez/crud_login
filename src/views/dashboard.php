<?php
    require('../../config/connection.php');
    require('../models/data.php');
    # Si no existe varible de sesion, quiere decir que el usuario no se ha autenticado
    # Negamos el acceso
    if (!isset($_SESSION['user'])) {
         header('Location: login.php');
         exit();
     }
     # Si existe, tomamos su nombre de usuario
    $username = $_SESSION['user'];

    $sql = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion FROM post WHERE autor_post != '$username' ORDER BY fecha_publicacion DESC";
    $result = $conn->query($sql);

    // Obtengo el el del usuario autenticado
    $userId = "SELECT id FROM usuarios WHERE usuario = '$username'";
    $getUser = $conn->query($userId);

    if ($getUser->num_rows > 0) {
        while ($row = $getUser->fetch_assoc()) {
            $id = $row['id'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../controllers/load-posts.js"></script>
    <script src="../helpers/toggle-menu.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../public/css/dashboard.css">
    <title>Home</title>
    <style>
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        .modal-content, .modal-body, .list-group, .list-group-item {
            color: #fff; /* Color del texto de los items de la lista */
            background-color: #141417; /* Color de fondo de los items de la lista */
        }
    </style>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <div class="menu-button" onclick="toggleMenu()">
        <img src="../../public/svg/menu.svg" alt="">
    </div>
    <main class="main-container">
        <aside>
            <details>
                <summary>MMORPG</summary>
            </details>
            <details>
                <summary>Shooter</summary>
            </details>
            <details>
                <summary>Strategy</summary>
            </details>
            <details>
                <summary>MOBA</summary>
            </details>
            <details>
                <summary>Racing</summary>
            </details>
            <details>
                <summary>Sports</summary>
            </details>
            <details>
                <summary>Social</summary>
            </details>
            <details>
                <summary>Sandbox</summary>
            </details>
            <details>
                <summary>Open World</summary>
            </details>
            <details>
                <summary>Survival</summary>
            </details>
            <details>
                <summary>PvP</summary>
            </details>
            <details>
                <summary>PvE</summary>
            </details>
            <details>
                <summary>Pixel</summary>
            </details>
            <details>
                <summary>Voxel</summary>
            </details>
            <details>
                <summary>Zombie</summary>
            </details>
            <details>
                <summary>Turn based</summary>
            </details>
            <details>
                <summary>First Person</summary>
            </details>
            <details>
                <summary>Third Person</summary>
            </details>
            <details>
                <summary>Top-down</summary>
            </details>
            <details>
                <summary>Tank</summary>
            </details>
            <details>
                <summary>Space</summary>
            </details>
            <details>
                <summary>Sailing</summary>
            </details>
            <details>
                <summary>Side-scroller</summary>
            </details>
            <details>
                <summary>Superhero</summary>
            </details>
            <details>
                <summary>Permadeath</summary>
            </details>
            <details>
                <summary>Card</summary>
            </details>
            <details>
                <summary>Battle-royale</summary>
            </details>
            <details>
                <summary>MMO</summary>
            </details>
            <details>
                <summary>MMOFPS</summary>
            </details>
            <details>
                <summary>MMOTPS</summary>
            </details>
            <details>
                <summary>2D</summary>
            </details>
            <details>
                <summary>3D</summary>
            </details>
            <details>
                <summary>Anime</summary>
            </details>
            <details>
                <summary>Fantasy</summary>
            </details>
            <details>
                <summary>Sci-Fi</summary>
            </details>
            <details>
                <summary>Fighting</summary>
            </details>
            <details>
                <summary>Action-RPG</summary>
            </details>
            <details>
                <summary>Action</summary>
            </details>
            <details>
                <summary>Military</summary>
            </details>
            <details>
                <summary>Martial-Arts</summary>
            </details>
            <details>
                <summary>Flight</summary>
            </details>
            <details>
                <summary>Low-spec</summary>
            </details>
            <details>
                <summary>Tower Defense</summary>
            </details>
            <details>
                <summary>Horror</summary>
            </details>
            <details>
                <summary>MMORTS</summary>
            </details>
        </aside>
        <div id="registros" class="registros"></div>
    </main>

    <div class="modal fade" id="likesModal" tabindex="-1" role="dialog" aria-labelledby="likesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="likesModalLabel">Users who liked this post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="likesList" class="list-group"></ul>
                </div>
            </div>
        </div>
    </div>

        <script>
            // Pasamos la variable PHP del id de nuestro usuario para almacenarla con Javascript,
            // y despues utilizarla para evaluar una respuesta con AJAX.
            var authUserId = <?php echo json_encode($id); ?>;
        </script>
</body>
</html>