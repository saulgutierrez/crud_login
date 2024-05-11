<?php
    require('conexion.php');
    require('data.php');
    # Si no existe varible de sesion, quiere decir que el usuario no se ha autenticado
    # Negamos el acceso
    if (!isset($_SESSION['user'])) {
         header('Location: ../index.php');
         exit;
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
    <header>
        <h1>Forum</h1>
        <input type="text">
        <!-- Identificamos al usuario dentro de la interfaz -->
        <h2 class="identifier" id="identifier">Bienvenido <?php echo $username; ?></h2>
        <div class="square"></div>
        <div class="dropdown">
            <p>Ver perfil</p>
            <p>Editar perfil</p>
            <p><a href="../php/logout.php">Cerrar sesion</a></p>
        </div>
        <h2><a href="">Nueva</a></h2>
    </header>
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