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

        </figure>
        <footer>

        </footer>
    </main>
</body>
</html>
<script src="../js/dashboard.js"></script>