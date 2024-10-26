<?php
    require('../../config/connection.php');
    require('../models/session.php');

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
            $idCategoria = $sqlGetInfoPost['id_categoria'];
        } else {
            header('Location dasboard.php');
        }

        $sqlGetCategories = "SELECT id_categoria, nombre_categoria FROM categorias";
        $resultGetCategories = $conn->query($sqlGetCategories);

    } else {
        header('Location: dashboard.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../handlers/edit-post.js"></script>
    <link rel="stylesheet" href="../../public/css/edit-post.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.0/tinymce.min.js"></script>
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
        <div class="category-content">
            <label for="category">Categoria</label>
            <div class="custom-select">
                <select name="category" id="category">
                    <?php if ($resultGetCategories->num_rows > 0) {
                        while ($row = $resultGetCategories->fetch_assoc()) {
                            // Comparamos el id de la categoria con el id de todas las categorias,
                            // para preseleccionar el que corresponde con el post que se está editando
                            $selected = ($row['id_categoria'] == $idCategoria) ? 'selected' : '';
                            echo "<option value='".$row['id_categoria']."'".$selected.">".$row['nombre_categoria']."</option>";
                        }
                    } else {
                        echo "<option>No existen categorias</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <input type="file" name="file" id="file" accept="image/*">
        <a href="#" id="openModalLink" class="<?php echo $hasImage; ?>">Ver imagen existente</a>
        <div class="image-content">
            <img src="" alt="" id="imagePreview">
            <img src="../../public/svg/close-circle.svg" alt="" class="close-icon" id="close-icon">
        </div>
        <div class="group-buttons">
            <a href="profile.php?user=<?php echo $user; ?>">
                <div class="imgBox">
                    <img src="../../public/svg/close-circle.svg" alt="">
                </div>
                <div>Cancelar</div>
            </a>
            <button value="Guardar cambios">
                <div class="imgBox">
                    <img src="../../public/svg/save.svg" alt="">
                </div>
                <div>Guardar</div>
            </button>
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
<script src="../helpers/view-saved-image.js"></script>
<script src="../helpers/image-preview-edit.js"></script>
<script src="../helpers/custom-select.js"></script>
<script>
    tinymce.init({
        selector:   '#post_content',
        plugins: 'image link media table emoticons',
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media emoticons',
        width: 450,
        height: 200,
        resize: false,
        skin: 'oxide-dark',
        content_css: 'dark',
    });
</script>
</html>