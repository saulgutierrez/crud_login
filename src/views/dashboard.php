<?php
    require('../../config/connection.php');
    require('../models/session.php');
    # Si no existe varible de sesion, quiere decir que el usuario no se ha autenticado
    # Negamos el acceso
    if (!isset($_SESSION['user'])) {
         header('Location: login.php');
         exit();
     }
     # Si existe, tomamos su nombre de usuario
    $username = $_SESSION['user'];

    // Obtengo el el del usuario autenticado
    $userId = "SELECT id FROM usuarios WHERE usuario = '$username'";
    $getUser = $conn->query($userId);

    if ($getUser->num_rows > 0) {
        while ($row = $getUser->fetch_assoc()) {
            $id = $row['id'];
        }
    }

    $sqlGetGameCategories = "SELECT * FROM categorias";
    $resultGetCategories = $conn->query($sqlGetGameCategories); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../handlers/get-follow-info.js"></script>
    <script src="../handlers/load-posts.js"></script>
    <script src="../ui/toggle-menu.js"></script>
    <link href="../../public/svg/forum-icon-black.svg" rel="icon" media="(prefers-color-scheme: light)">
    <link href="../../public/svg/forum-icon-white.svg" rel="icon" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/photoswipe.min.css" integrity="sha512-LFWtdAXHQuwUGH9cImO9blA3a3GfQNkpF2uRlhaOpSbDevNyK1rmAjs13mtpjvWyi+flP7zYWboqY+8Mkd42xA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/umd/photoswipe-lightbox.umd.min.js" integrity="sha512-D16CBrIrVF48W0Ou0ca3D65JFo/HaEAjTugBXeWS/JH+1KNu54ZOtHPccxJ7PQ44rTItUT6DSI6xNL+U34SuuQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/umd/photoswipe.umd.min.js" integrity="sha512-BXwwGU7zCXVgpT2jpXnTbioT9q1Byf7NEXVxovTZPlNvelL2I/4LjOaoiB2a19L+g5za8RbkoJFH4fMPQcjFFw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/photoswipe-lightbox.esm.min.js" integrity="sha512-S9RkWnGja84tXKFxTN7iLVP3pUCsnfqnF+0ZK2CSOhmCqa6lxoutHUoizBVnqCIsH8HW7e/3u9HEOOwlR01TLA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/photoswipe.esm.min.js" integrity="sha512-AyqbkQ0CCFXttmj38AAryPYIKEOdL6lApyzLje2dyvMwLoHv7PPXIeKS86gF4V85Gv+ZsCiOSP0yHaCXcemmaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Core CSS file -->
    <link rel="stylesheet" href="https://unpkg.com/photoswipe@4.1.3/dist/photoswipe.css">
    <!-- Skin CSS file (styling of UI - buttons, etc.) -->
    <link rel="stylesheet" href="https://unpkg.com/photoswipe@4.1.3/dist/default-skin/default-skin.css">
    <!-- Core JS file -->
    <script src="https://unpkg.com/photoswipe@4.1.3/dist/photoswipe.min.js"></script>
    <!-- UI JS file -->
    <script src="https://unpkg.com/photoswipe@4.1.3/dist/photoswipe-ui-default.min.js"></script>
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

        .modal-content {
            height: auto;
            max-height: 300px;
            overflow-y: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <div class="menu-button" onclick="toggleMenu()">
        <img src="../../public/svg/menu.svg" alt="">
    </div>
    <main class="main-container">
        <aside id="category-menu">
            <?php
                if ($resultGetCategories->num_rows > 0) {
                    while ($rowCategories = $resultGetCategories->fetch_assoc()) {
                        $category_id = $rowCategories['id_categoria'];
                        $category_name = $rowCategories['nombre_categoria'];
            ?>
            <details>
                <summary><a href="#" data-category="<?php echo $category_id; ?>"><?php echo $category_name; ?></a></summary>
            </details>
            <?php
                    }
                }
            ?>
        </aside>
        <div id="registros" class="registros"></div>
        <div class="suggestions" id="suggestions">
            <div class="title">Recomendado para tí</div>
            <!-- <div class="title">People you may know</div>
            <div class="title">Trends</div> -->
        </div>
    </main>

    <div class="modal fade" id="likesModal" tabindex="-1" role="dialog" aria-labelledby="likesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="likesModalLabel">Likes</h5>
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

    <!-- PhotoSwipe Gallery -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Share"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pasamos la variable PHP del id de nuestro usuario para almacenarla con Javascript,
        // y despues utilizarla para evaluar una respuesta con AJAX.
        var authUserId = <?php echo json_encode($id); ?>;
    </script>
    <script src="../handlers/get-knn-results.js" defer></script>
</body>
</html>