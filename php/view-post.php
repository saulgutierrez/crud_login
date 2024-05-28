<?php
    require('conexion.php');
    require('data.php');

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
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/view-post.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/new-comment.js"></script>
    <title><?php echo $titulo; ?></title>
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <main id="main-container">
        <div class="post-card">
            <a href="dashboard.php">
                <img src="../svg/arrow-back.svg" alt="" class="arrow-back">
            </a>
            <h2 class="post-title">Post</h2>
            <h2><a href="profile.php?id=<?php echo $id;?>"><?php echo $autor; ?></a></h2>
            <h3><?php echo $titulo; ?></h3>
            <div><?php echo $contenido; ?></div>
            <form class="group-comment" id="form-comment" method="POST">
                <input type="hidden" value="<?php echo $id_post; ?>" id="id-post" name="id-post">
                <input type="hidden" value="<?php echo $id; ?>" id="id-autor-post" name="id-autor-post">
                <input type="hidden" value="<?php echo $username; ?>" id="autor-comentario" name="autor-comentario">
                <input type="text" placeholder="Escribe un comentario..." class="comment-input" id="comment-input" name="comment-input">
                <button value="Responder">Responder</button>
            </form>
        </div>
        <?php
            $sqlComments = "SELECT * FROM comentarios WHERE id_post = '$id_post'";
            $playQuery = $conn->query($sqlComments);

            $contador = 0;
            if ($playQuery->num_rows > 0) {
                while ($fila = $playQuery->fetch_assoc()) {
                    $contador++;
                    $postId = $fila['id_post'];
                    $autorId = $fila['id_autor'];
                    $comentarioId = $fila['id_comentario'];
                    $autorComentario = $fila['autor_comentario'];
                    $comentario = $fila['comentario'];
        ?>

        <div class="post-card comment">
            <h3><?php echo $autorComentario; ?></h3>
            <div><?php echo $comentario; ?></div>
        </div>
        <?php
                }
            }
        ?>
    </main>
</body>
</html>