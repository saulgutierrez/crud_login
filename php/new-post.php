<?php
    require('conexion.php');
    require('data.php');

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
    } else {
        header('Location: dashboard.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/new-post.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/new-post.js"></script>
    <title>Crear Post</title>
</head>
<body>
    <form method="POST" id="newPostForm">
        <input type="hidden" id="id_user" name="id_user" value="<?php echo $idPerfil;?>">
        <input type="hidden" id="user" name="user" value="<?php echo $user;?>">
        <label for="post-title">Titulo</label>
        <input type="text" name="post_title" id="post_title" class="post_title" placeholder="Titulo del post">
        <label for="post-content">Contenido</label>
        <textarea name="post_content" id="post_content" rows="4" cols="35"></textarea>
        <div class="group-buttons">
            <a href="profile.php?user=<?php echo $user; ?>">Cancelar</a>
            <button value="Guardar cambios">Guardar cambios</button>
        </div>
        <div id="new-post-result" class="new-post-result"></div>
    </form>
</body>
</html>