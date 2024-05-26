<?php
    require('conexion.php');
    require('data.php');
    # Si no existe varible de sesion, quiere decir que el usuario no se ha autenticado
    # Negamos el acceso
    if (!isset($_SESSION['user'])) {
         header('Location: ../index.php');
         exit();
     }
     # Si existe, tomamos su nombre de usuario
    $username = $_SESSION['user'];

    $sql = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post FROM post WHERE autor_post != '$username'";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <figure>
            <?php
                $counter = 0;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $counter++;
                        $id_post = $row['id_post'];
                        $id = $row['id_autor'];
                        $autor = $row['autor_post'];
                        $titulo = $row['titulo_post'];
                        $contenido = $row['contenido_post'];
            ?>
            <div class="post-card" onclick="window.location.href='view-post.php?id=<?php echo $id_post;?>'">
                <h2><a href="profile.php?id=<?php echo $id;?>"><?php echo $autor; ?></a></h2>
                <h3><?php echo $titulo; ?></h3>
                <div><?php echo $contenido; ?></div>
            </div>
            <?php
                    }
                }
            ?>
        </figure>
    </main>
</body>
</html>