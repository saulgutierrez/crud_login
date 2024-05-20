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

    $sql = "SELECT autor_post, titulo_post, contenido_post FROM post";
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
                        $autor = $row['autor_post'];
                        $titulo = $row['titulo_post'];
                        $contenido = $row['contenido_post'];
                
            ?>

            <div><?php echo $autor; ?></div>
            <div><?php echo $titulo; ?></div>
            <div><?php echo $contenido; ?></div>


            <?php
                    }
                }
            ?>
        </figure>
    </main>
</body>
</html>