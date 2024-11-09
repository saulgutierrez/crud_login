<?php
    require('../../config/connection.php');
    require('../models/session.php');
    date_default_timezone_set('America/Mexico_City');
    $fecha_actual = date("d-m-Y H:i:s");

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];

        $sqlGetInfo = "SELECT id FROM usuarios WHERE usuario = '$user'";
        $queryGetInfo = mysqli_query($conn, $sqlGetInfo);

        if ($sqlGetInfoPost = mysqli_fetch_assoc($queryGetInfo)) {
            $idPerfil = $sqlGetInfoPost['id'];
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
    <link href="../../public/svg/forum-icon-black.svg" rel="icon" media="(prefers-color-scheme: light)">
    <link href="../../public/svg/forum-icon-white.svg" rel="icon" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="../../public/css/new-post.css">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../handlers/new-post.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.0/tinymce.min.js"></script>
    <title>Crear Post</title>
</head>

<body>
    <form method="POST" id="newPostForm" enctype="multipart/form-data">
        <input type="hidden" id="id_user" name="id_user" value="<?php echo $idPerfil;?>">
        <input type="hidden" id="user" name="user" value="<?php echo $user;?>">
        <input type="text" name="post_title" id="post_title" class="post_title" placeholder="Titulo del post">
        <textarea name="post_content" id="post_content" rows="4" cols="35"></textarea>
        <div class="category-content">
            <label for="category">Categoria</label>
            <div class="custom-select">
                <select name="category" id="category">
                <?php if ($resultGetCategories->num_rows > 0) {
                    while ($row = $resultGetCategories->fetch_assoc()) {
                        echo "<option value='".$row['id_categoria']."'>".$row['nombre_categoria']."</option>";
                    }
                } else {
                    echo "<option>No existen categorias</option>";
                }
                ?>
                </select>
            </div>
            <div class="image-upload">
                <label for="file">
                    <img src="../../public/svg/image-icon.svg">
                </label>
                <input type="file" name="file" id="file" accept="image/*">
            </div>
        </div>
        <input name="post_time" id="post_time" type="hidden" value="<?php echo $fecha_actual; ?>">
        <div class="image-content">
            <img src="" alt="" id="imagePreview">
            <img src="../../public/svg/close-circle.svg" alt="" class="close-icon" id="close-icon">
        </div>
        <div class="group-buttons">
            <a onclick="history.back()">
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
        <div id="new-post-result" class="new-post-result"></div>
    </form>
    <script src="../ui/image-preview.js"></script>
    <script src="../ui/custom-select.js"></script>
    <script>
        tinymce.init({
            selector:   '#post_content',
            plugins: 'link emoticons codesample lists',
            toolbar: 'bold italic underline strikethrough superscript | link emoticons codesample | numlist bullist',
            width: 450,
            height: 200,
            resize: false,
            skin: 'oxide-dark',
            content_css: 'dark',
            statusbar: false,
            menubar: false,
            branding: false,
    });
    </script>
</body>
</html>