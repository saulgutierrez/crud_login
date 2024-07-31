<?php
    require('../../config/connection.php');
    require('../models/data.php');

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
            $id = $row['id_autor'];
            $autor = $row['autor_post'];
            $titulo = $row['titulo_post'];
            $contenido = $row['contenido_post'];
            $foto = $row['foto_post'];
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/view-post.css">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
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
                <h2><a href="profile.php?id=<?php echo $id;?>"><?php echo $autor; ?></a></h2>
                <div><?php echo $fecha; ?></div>
            </div>
            <h3><?php echo $titulo; ?></h3>
            <div><?php echo $contenido; ?></div>
            <img src="<?php echo $foto; ?>" alt="">
            <form class="group-comment" id="form-comment" method="POST">
                <input type="hidden" value="<?php echo $id_post; ?>" id="id-post" name="id-post">
                <input type="hidden" value="<?php echo $id; ?>" id="id-autor-post" name="id-autor-post">
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
                <button value="Responder" id="send-comment">Responder</button>
            </form>
            <div class="image-content">
                <img src="" alt="" id="imagePreview">
                <img src="../../public/svg/close-circle.svg" alt="" class="close-icon" id="close-icon">
            </div>
        </div>
        <?php
            $sqlComments = "SELECT * FROM comentarios WHERE id_post = '$id_post' ORDER BY fecha_publicacion DESC";
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
                    $fecha = $fila['fecha_publicacion'];
        ?>

        <div class="post-card comment">
            <div class="comment-card-top">
                <?php
                    // Evaluamos si el autor de cada comentario corresponde con el id del autor que tiene la sesión
                    // iniciada o no, para mostrar la pantalla que corresponde.
                    $redirect;
                    if ($idAutorComentarioArray == $idAutorComentario) {
                        $redirect = "profile.php?user=$username";
                    } else {
                        $redirect = "profile.php?id=$idAutorComentarioArray";
                    }
                ?>
                <a class="comment-user" href="<?php echo $redirect; ?>"><?php echo $autorComentario; ?></a>
                <div><?php echo $fecha; ?></div>
            </div>
            <div><?php echo $comentario; ?></div>
            <img src="<?php echo $imagen; ?>" alt="">
        </div>
        <?php
                }
            }
        ?>
    </main>
    <script src="../helpers/comment-image-preview.js"></script>
    <script src="../helpers/get-current-time.js"></script>
</body>
</html>