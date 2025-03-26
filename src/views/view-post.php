<?php
    require('../../config/connection.php');
    require('../models/session.php');
    date_default_timezone_set('America/Mexico_City'); 

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header('Location: ../index.php');
        exit();
    }
    $username = $_SESSION['user'];
    $idPost = $_GET['id'];

    $sql = "SELECT * FROM post WHERE id_post = '$idPost'";
    $result = $conn->query($sql);

    $counter = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $counter++;
            $id_post = $row['id_post'];
            $id_autor = $row['id_autor'];
            $autor = $row['autor_post'];
            $titulo = $row['titulo_post'];
            $contenido = $row['contenido_post'];
            $fotoPost = $row['foto_post'];
            $fecha = $row['fecha_publicacion'];
        }
    }

    $sql2 = "SELECT id FROM usuarios WHERE usuario = '$username'";
    $result2 = $conn->query($sql2);

    $counter2 = 0;
    if ($result2->num_rows > 0) {
        while ($row2 = $result2->fetch_assoc()) {
            $counter2++;
            $idAutorComentario = $row2['id'];
        }
    }

    // Obtener la imagen del usuario que genero el posteo
    $sql3 = "SELECT u.fotografia FROM usuarios u JOIN post p ON p.id_autor = u.id WHERE p.id_post = $id_post";
    $result3 = $conn->query($sql3);

    $counter3 = 0;
    if ($result3->num_rows > 0) {
        while ($row3 = $result3->fetch_assoc()) {
            $counter3++;
            $fotoUsuario = $row3['fotografia'];
        }
    }

    // Consulta para obtener el numero de "likes" de cada post
    $likes_sql = "SELECT COUNT(*) as like_count FROM likes WHERE liked_id_post = '$id_post'";
    $likes_result = $conn->query($likes_sql);
    $likes_row = $likes_result->fetch_assoc();
    $like_count = $likes_row['like_count'];

    // Consulta para obtener el numero de comentarios del post
    $comments_sql = "SELECT COUNT(*) as comment_count FROM comentarios WHERE id_post = '$id_post'";
    $comments_result = $conn->query($comments_sql);
    $comments_row = $comments_result->fetch_assoc();
    $comment_count = $comments_row['comment_count'];

    // Si venimos al post desde una notificacion, la marcamos como leido en la base de datos
    if (isset($_GET['notif_id'])) {
        $notif_id = $_GET['notif_id'];

        $sqlNotif = "UPDATE notificaciones SET leida = 1 WHERE id_notificacion = $notif_id";
        mysqli_query($conn, $sqlNotif);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../public/svg/forum-icon-black.svg" rel="icon" media="(prefers-color-scheme: light)">
    <link href="../../public/svg/forum-icon-white.svg" rel="icon" media="(prefers-color-scheme: dark)">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../handlers/get-follow-info-thread.js"></script>
    <script src="../handlers/get-follow-info-comments.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../public/css/view-post.css">
    <script src="../handlers/new-comment.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/photoswipe.min.css" integrity="sha512-LFWtdAXHQuwUGH9cImO9blA3a3GfQNkpF2uRlhaOpSbDevNyK1rmAjs13mtpjvWyi+flP7zYWboqY+8Mkd42xA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/umd/photoswipe-lightbox.umd.min.js" integrity="sha512-D16CBrIrVF48W0Ou0ca3D65JFo/HaEAjTugBXeWS/JH+1KNu54ZOtHPccxJ7PQ44rTItUT6DSI6xNL+U34SuuQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/umd/photoswipe.umd.min.js" integrity="sha512-BXwwGU7zCXVgpT2jpXnTbioT9q1Byf7NEXVxovTZPlNvelL2I/4LjOaoiB2a19L+g5za8RbkoJFH4fMPQcjFFw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/photoswipe@5.3.8/dist/photoswipe-lightbox.min.js"></script>
    <title><?php echo $titulo; ?></title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <main id="main-container">
        <div class="post-card post-card-item" id="post-card">
            <a onclick="history.back()">
                <img src="../../public/svg/arrow-back.svg" alt="" class="arrow-back">
            </a>
            <h2 class="post-title">Post</h2>
            <hr>
            <div class="post-card-top">
                <h2>
                    <div class="imgBoxProfileImage">
                        <img src="<?php echo $fotoUsuario; ?>" alt="">
                    </div>
                    <?php
                    // Determinar si el autor del post es el usuario actual para redirigir al perfil adecuado
                    $redirectPost;
                    if ($id_autor == $idAutorComentario) {
                        $redirectPost = "profile.php?user=$username";
                    } else {
                        $redirectPost = "profile.php?id=$id_autor";
                    }
                    ?>
                    <a href="<?php echo $redirectPost; ?>" data-id="<?php echo $id_autor; ?>" data-autor="<?php echo $autor; ?>" data-foto="<?php echo $fotoUsuario; ?>" id="username">
                        <?php echo $autor; ?>
                    </a>
                </h2>
                <div class="fecha" data-fecha="<?php echo htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="fecha-formateada"></div>
            </div>
            <h3>
                <div><?php echo $titulo; ?></div>
            </h3>
            <div><?php echo $contenido; ?></div>
            <?php if (!empty($fotoPost)) : ?>
                <div class="my-gallery">
                    <figure class="photo-content">
                        <a href="<?php echo $fotoPost; ?>" data-pswp-width="800" data-pswp-height="800" class="pswp-link" data-pswp-index="1" onclick="return false;">
                            <img src="<?php echo $fotoPost; ?>" alt="Photo 1">
                        </a>
                    </figure>
                </div>
            <?php endif; ?>
        </div>
        <div class="comments-content">
            <div class="comments-list" id="comments-list">
        <?php
        $sqlComments = "SELECT c.*, u.fotografia 
                        FROM comentarios c 
                        JOIN usuarios u ON c.id_autor_comentario = u.id 
                        LEFT JOIN user_blocks b
                            ON (b.blocked_id = c.id_autor_comentario AND b.blocker_id = '$idAutorComentario')
                            OR (b.blocker_id = c.id_autor_comentario AND b.blocked_id = '$idAutorComentario')
                        WHERE b.blocked_id IS NULL AND c.id_post = '$id_post'
                        ORDER BY c.fecha_publicacion DESC";
        $playQuery = $conn->query($sqlComments);

        $contador = 0;
        if ($playQuery->num_rows > 0) {
            while ($fila = $playQuery->fetch_assoc()) {
                $contador++;
                $postId = $fila['id_post'];
                $autorId = $fila['id_autor'];
                $comentarioId = $fila['id_comentario'];
                $idAutorComentarioArray = $fila['id_autor_comentario'];
                $autorComentario = $fila['autor_comentario'];
                $comentario = $fila['comentario'];
                $imagen = $fila['foto_comentario'];
                $formato = 'd/m/Y, g:i:s A';
                $fecha = $fila['fecha_publicacion'];
                $fotografiaAutorComentario = $fila['fotografia']; // Fotografía del usuario

                // Consulta para contar los likes de este comentario
                $likes_comments_sql = "SELECT COUNT(*) as likes_count_comment FROM likes_comentarios WHERE id_comentario = '$comentarioId'";
                $likes_comments_result = $conn->query($likes_comments_sql);
                $like_count_comment = 0;
                if ($likes_comments_result) {
                    $likes_comments_row = $likes_comments_result->fetch_assoc();
                    $like_count_comment = $likes_comments_row['likes_count_comment'];
                }
        ?>
            <div class="post-card comment">
                <div class="comment-card-top">
                    <?php
                    // Determinar si el autor del comentario es el usuario actual para redirigir al perfil adecuado
                    $redirect;
                    if ($idAutorComentarioArray == $idAutorComentario) {
                        $redirect = "profile.php?user=$username";
                    } else {
                        $redirect = "profile.php?id=$idAutorComentarioArray";
                    }
                    ?>
                    <h2>
                        <div class="imgBoxProfileImage">
                            <img src="<?php echo $fotografiaAutorComentario; ?>" alt="">
                        </div>
                        <a class="comment-user" href="<?php echo $redirect; ?>" data-id="<?php echo $idAutorComentarioArray; ?>" data-autor="<?php echo $autorComentario; ?>" data-foto="<?php echo $fotografiaAutorComentario; ?>"><?php echo $autorComentario; ?></a>
                    </h2>
                    <div class="fecha-comment" data-fecha="<?php echo htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="fecha-formateada"></div>
                </div>
                <div class="comment-card-body">
                    <div class="comment-content"><?php echo $comentario; ?></div>
                    <!-- Mostrar el número de likes junto al botón de "Like" -->
                    <a href="#" class="like-count-comment" data-id="<?php echo $comentarioId; ?>" data-toggle="modal" data-target="#likesModal">
                        <?php echo $like_count_comment; ?>
                    </a>
                    <div class="imgBoxLikeComment">
                        <img src="../../public/svg/heart.svg" alt="">
                    </div>
                    <a class="like-button-comment" data-id="<?php echo $comentarioId; ?>">Like</a>
                </div>
                <?php if (!empty($imagen)) { ?>
                <div class="my-gallery">
                    <figure class="photo-content-comment">
                        <a href="<?php echo $imagen; ?>" data-pswp-width="800" data-pswp-height="800" class="pswp-link" data-pswp-index="1" onclick="return false;">
                            <img src="<?php echo $imagen; ?>" alt="Photo 1">
                        </a>
                    </figure>
                </div>
                <?php } ?>
            </div>
        <?php
            }
        }
        ?>
        </div>
            <div class="interactive-container">
                <div class="stats-container">
                    <div class="stats-container-child">
                        <a href="#" class="like-count" data-id=" <?php echo $id_post; ?> " data-toggle="modal" data-target="#likesModal"> <?php echo $like_count; ?> </a>
                        <div class="comment-count"><?php echo $comment_count; ?></div>
                    </div>
                    <a class="like-button" data-id="<?php echo $id_post; ?>">Like</a>
                    <div class="imgBoxLike">
                        <img src="../../public/svg/heart.svg" alt="">
                    </div>
                    <div class="imgBoxComment">
                        <img src="../../public/svg/comment.svg" alt="">
                    </div>
                </div>
                <hr>
                <form class="group-comment" id="form-comment" method="POST">
                    <input type="hidden" value="<?php echo $id_post; ?>" id="id-post" name="id-post">
                    <input type="hidden" value="<?php echo $id_autor; ?>" id="id-autor-post" name="id-autor-post">
                    <input type="hidden" value="<?php echo $idAutorComentario; ?>" id="id-autor-comentario" name="id-autor-comentario">
                    <input type="hidden" value="<?php echo $username; ?>" id="autor-comentario" name="autor-comentario">
                    <input type="text" placeholder="Comentar" class="comment-input" id="comment-input" name="comment-input">
                    <input type="hidden" name="comment-time" id="comment-time">
                    <div class="image-upload">
                        <label for="file-input">
                            <img src="../../public/svg/image-icon.svg" alt="">
                        </label>
                        <input type="file" class="file-input" id="file-input" name="file-input">
                    </div>
                    <button value="Responder" id="send-comment">
                        <div class="imgBox">
                            <img src="../../public/svg/reply.svg" alt="">
                        </div>
                        <div>Responder</div>
                    </button>
                </form>
                <div class="image-content">
                    <img src="" alt="" id="imagePreview">
                    <img src="../../public/svg/close-circle.svg" alt="" class="close-icon" id="close-icon">
                </div>
            </div>
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
                    <button class="pswp__button pswp__button--close" title="Cerrar (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Compartir"></button>
                    <button class="pswp__button pswp__button--fs" title="Pantalla completa"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom"></button>
                    <div class="pswp__counter"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="Anterior (flecha izquierda)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Siguiente (flecha derecha)"></button>
            </div>
        </div>
    </div>

    <script src="../ui/comment-image-preview.js"></script>
    <script src="../ui/get-current-time.js"></script>
    <script src="../handlers/like-comment.js"></script>
    <script src="../handlers/check-like-button-state-thread.js"></script>
    <script src="../ui/view-full-date.js"></script>
    <script src="../ui/view-full-date-comment.js"></script>
    <script src="../ui/view-post.js"></script>
    <script type="module" src="../ui/photo-gallery.js"></script>
    <!-- date-fns -->
    <script src="https://cdn.jsdelivr.net/npm/date-fns@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/4.1.0/locale/cdn.min.js" integrity="sha512-JSQaWOYLr6A/XyM8RJ0G0zhxvaX/PEzbH61gH77hj8UtE6BKhpYemtDCDVS0nDBsT5h3azCkK9pOABcC5ioGmw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Pasamos la variable PHP del id de nuestro usuario para almacenarla con Javascript,
        // y despues utilizarla para evaluar una respuesta con AJAX.
        var authUserId = <?php echo json_encode($idAutorComentario); ?>
    </script>
</body>
</html>