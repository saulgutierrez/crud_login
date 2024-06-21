<?php
    require('connection.php');
    require('data.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $id_post = $_GET['id_post'];

        $sqlGetInfo = "SELECT * FROM post WHERE id_post = '$id_post'";
        $queryGetInfo = mysqli_query($conn, $sqlGetInfo);

        if ($sqlGetInfoPost = mysqli_fetch_assoc($queryGetInfo)) {
            $idAutor = $sqlGetInfoPost['id_autor'];
            $autorPost = $sqlGetInfoPost['autor_post'];
            $tituloPost = $sqlGetInfoPost['titulo_post'];
            $contenidoPost = $sqlGetInfoPost['contenido_post'];
            $fotoPost = $sqlGetInfoPost['foto_post'];
            $hasImage = !empty($fotoPost) ? 'imgLinkLabel' : 'noImage';
        } else {
            header('Location dasboard.php');
        } 
    } else {
        header('Location: dashboard.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/edit-post.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/edit-post.js"></script>
    <title>Editar Post</title>
</head>
<body>
    <form method="POST" id="editPostForm">
        <!-- Id del post, para editar sobre un registro especifico -->
        <input type="hidden" id="id_post" name="id_post" value="<?php echo $id_post; ?>">
        <!-- Id del autor del post = id del usuario -->
        <input type="hidden" id="id_user" name="id_user" value="<?php echo $idAutor; ?>">
        <!-- Nombre del autor del post -->
        <input type="hidden" id="user" name="user" value="<?php echo $autorPost; ?>">
        <label for="post-title">Titulo</label>
        <input type="text" name="post_title" id="post_title" class="post_title" placeholder="Titulo del post" value="<?php echo $tituloPost; ?>">
        <label for="post-content">Contenido</label>
        <textarea name="post_content" id="post_content" rows="4" cols="35"><?php echo $contenidoPost; ?></textarea>
        <input type="file" name="file" id="file" accept="image/*">
        <a href="#" id="openModalLink" class="<?php echo $hasImage; ?>">Ver imagen existente</a>
        <div class="group-buttons">
            <a href="profile.php?user=<?php echo $user; ?>">Cancelar</a>
            <button value="Guardar cambios">Guardar cambios</button>
        </div>
        <div id="edit-post-result" class="edit-post-result"></div>
    </form>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img src="<?php echo $fotoPost; ?>" style="max-width: 100%; height: auto;">
        </div>
    </div>

</body>
<script src="../js/view-saved-image.js"></script>
</html>