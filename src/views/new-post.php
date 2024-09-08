<?php
    require('../../config/connection.php');
    require('../models/session.php');
    date_default_timezone_set('America/Mexico_City');
    $fecha_actual = date("d-m-Y h:i:s");

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
    <link rel="stylesheet" href="../../public/css/new-post.css">
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../controllers/new-post.js"></script>
    <title>Crear Post</title>
</head>

<body>
    <form method="POST" id="newPostForm" enctype="multipart/form-data">
        <input type="hidden" id="id_user" name="id_user" value="<?php echo $idPerfil;?>">
        <input type="hidden" id="user" name="user" value="<?php echo $user;?>">
        <label for="post-title">Titulo</label>
        <input type="text" name="post_title" id="post_title" class="post_title" placeholder="Titulo del post">
        <label for="post-content">Contenido</label>
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
        </div>
        <input name="post_time" id="post_time" type="hidden" value="<?php echo $fecha_actual; ?>">
        <input type="file" name="file" id="file" accept="image/*">
        <div class="image-content">
            <img src="" alt="" id="imagePreview">
            <img src="../../public/svg/close-circle.svg" alt="" class="close-icon" id="close-icon">
        </div>
        <div class="group-buttons">
            <a onclick="history.back()">
                <div class="imgBox">
                    <img src="../../public/svg/cancel.svg" alt="">
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
    <script src="../helpers/image-preview.js"></script>
    <script src="../helpers/custom-select.js"></script>
</body>
</html>