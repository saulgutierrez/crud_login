<?php
    require('connection.php');
    require('data.php');
    # Si no existe varible de sesion, quiere decir que el usuario no se ha autenticado
    # Negamos el acceso
    if (!isset($_SESSION['user'])) {
         header('Location: ../index.php');
         exit();
     }
     # Si existe, tomamos su nombre de usuario
    $username = $_SESSION['user'];

    $sql = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion FROM post WHERE autor_post != '$username' ORDER BY fecha_publicacion DESC";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/save-likes.js"></script>
    <script src="../js/load-records.js"></script>
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>Home</title>
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <main>
        <aside>
            <details>
                <summary>Games</summary>
                <p>Action Games</p>
                <p>Adventure Games</p>
            </details>
            <details>
                <summary>Tecnology</summary>
                <p>3D Printers</p>
                <p>Education</p>
            </details>
        </aside>
        <div id="registros" class="registros"></div>
    </main>
</body>
</html>