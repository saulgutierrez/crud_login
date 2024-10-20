<?php
    require('../../config/connection.php');
    require('../models/session.php');
    require '../../public/libs/Carbon/autoload.php';

    use Carbon\Carbon;
    Carbon::setLocale('es');

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
            $fecha = Carbon::parse($row['fecha_publicacion']);
            $fechaFormateada = $fecha->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] h:mm a');
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
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../controllers/get-follow-info-thread.js"></script>
    <script src="../controllers/get-follow-info-comments.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../public/css/view-post.css">
    <script src="../controllers/new-comment.js"></script>
    <title><?php echo $titulo; ?></title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <main id="main-container">
        <div class="post-card" id="post-card">
            <a onclick="history.back()">
                <img src="../../public/svg/arrow-back.svg" alt="" class="arrow-back">
            </a>
            <h2 class="post-title">Post</h2>
            <div class="post-card-top">
                <h2>
                    <div class="imgBoxProfileImage">
                        <img src="<?php echo $fotoUsuario; ?>" alt="">
                    </div>
                    <a href="profile.php?id=<?php echo $id_autor;?>" data-id="<?php echo $id_autor; ?>" data-autor="<?php echo $autor; ?>" data-foto="<?php echo $fotoUsuario?>">
                        <?php echo $autor; ?>
                    </a>
                </h2>
                <div class="fecha"><?php echo $fecha->diffForHumans(); ?></div>
                <div class="fecha-formateada"><?php echo $fechaFormateada; ?></div>
            </div>
            <h3>
                <div><?php echo $titulo; ?></div>
            </h3>
            <div><?php echo $contenido; ?></div>
            <img src="<?php echo $fotoPost; ?>" alt="">
            <a href="#" class="like-count" data-id=" <?php echo $id_post; ?> " data-toggle="modal" data-target="#likesModal"> <?php echo $like_count; ?> </a>
            <div class="comment-count"><?php echo $comment_count; ?></div>
            <a class="like-button" data-id="<?php echo $id_post; ?>">Like</a>
            <div class="imgBoxLike">
                <img src="../../public/svg/heart.svg" alt="">
            </div>
            <div class="imgBoxComment">
                <img src="../../public/svg/comment.svg" alt="">
            </div>
            <hr>
            <hr class="bottom-border">
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
        <?php
        $sqlComments = "SELECT c.*, u.fotografia 
                        FROM comentarios c 
                        JOIN usuarios u ON c.id_autor_comentario = u.id 
                        WHERE c.id_post = '$id_post' 
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
                $fecha = Carbon::parse($fila['fecha_publicacion']);
                $fechaFormateada = $fecha->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] h:mm a');
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
                        <div class="fecha"><?php echo $fecha->diffForHumans(); ?></div>
                        <div class="fecha-formateada"><?php echo $fechaFormateada; ?></div>
                    </div>
                    <div class="comment-card-body">
                        <div><?php echo $comentario; ?></div>
                        <!-- Mostrar el número de likes junto al botón de "Like" -->
                        <a href="#" class="like-count-comment" data-id="<?php echo $comentarioId; ?>" data-toggle="modal" data-target="#likesModal">
                            <?php echo $like_count_comment; ?> Likes
                        </a>
                        <a class="like-button-comment" data-id="<?php echo $comentarioId; ?>">Like</a>
                    </div>
                    <?php if ($imagen) { ?>
                        <img src="<?php echo $imagen; ?>" alt="">
                    <?php } ?>
                </div>
        <?php
            }
        }
        ?>
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
    <script src="../helpers/comment-image-preview.js"></script>
    <script src="../helpers/get-current-time.js"></script>
    <script src="../controllers/like-comment.js"></script>
    <script src="../controllers/check-like-button-state-thread.js"></script>
    <script src="../helpers/view-full-date.js"></script>
    <script>
        // Pasamos la variable PHP del id de nuestro usuario para almacenarla con Javascript,
        // y despues utilizarla para evaluar una respuesta con AJAX.
        var authUserId = <?php echo json_encode($idAutorComentario); ?>
    </script>
</body>
</html>