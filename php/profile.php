<?php
    require('conexion.php');
    require('data.php');

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user']; # Si el usuario esta logueado en el sistema, recibimos el nombre de usuario
        # Consultamos por el nombre de usuario en la base de datos
        $sqlGetInfo = "SELECT * FROM usuarios WHERE usuario = '$user'";
        $queryGetInfo = mysqli_query($conn, $sqlGetInfo);

        # Recuperamos la informacion asociada a la consulta
        if ($sqlGetProfile = mysqli_fetch_assoc($queryGetInfo)) {
            $idPerfil = $sqlGetProfile['id'];
            $nombreUsuario = $sqlGetProfile['usuario'];
            $nombrePerfil = $sqlGetProfile['nombre'];
            $apellidoPerfil = $sqlGetProfile['apellido'];
            $correoPerfil = $sqlGetProfile['correo'];
            $telefonoPerfil = $sqlGetProfile['telefono'];
            $fechaNacimientoPerfil = $sqlGetProfile['fecha_nacimiento'];
            $generoPerfil = $sqlGetProfile['genero'];
            $foto = $sqlGetProfile['fotografia'];
            $rutaFotoPorDefecto = "../img/profile-default.svg";
        } else { # En caso de que no exista el perfil, redireccionamos a el dashboard principal
            header('Location: dashboard.php');
            exit();
        }
    } else {
        header('Location: dashboard.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">
    <title><?php if ($nombrePerfil != '' && $apellidoPerfil != '') { echo $nombrePerfil. " ".$apellidoPerfil; } else { echo "anon"; } ?> | Forum</title>
</head>

<?php include "../includes/header.php"; ?>

<body>
    <main>
        <nav>
            <div>
                <figure>
                    <img src="<?php if ($foto !=  '') { echo $foto; } else { echo $rutaFotoPorDefecto; } ?>" alt="">
                </figure>
                <article>
                    <h2><?php if ($nombrePerfil != '' && $apellidoPerfil != '') { echo $nombrePerfil. " ".$apellidoPerfil; } else { echo "anon"; } ?></h2>
                    <h3><?php echo $nombreUsuario; ?></h3>
                </article>
            </div>
            <article>
                <a class="edit-profile-btn" href="edit-profile.php?user=<?php echo $user;?>">Editar perfil</a>
            </article>
        </nav>
        <section>
            <div class="menu-lateral">
                <a class="info" href="profile.php">Info</a>
                <a class="posts">Posts</a>
            </div>
            <div class="info-perfil">
                <!-- Mostrar informacion referente al perfil, o los posteos del perfil -->
                <h1>
                    <div>Nombre: </div> 
                    <div> <?php if ($nombrePerfil != '' && $apellidoPerfil != '') { echo $nombrePerfil. " ".$apellidoPerfil; } else { echo "anon"; } ?> </div>
                </h1>
                <h1>
                    <div>Correo: </div>
                    <div><?php echo $correoPerfil;?></div>
                </h1>
                <h1>
                    <div>Telefono: </div>
                    <div><?php echo $telefonoPerfil; ?></div>
                </h1>
                <h1>
                    <div>Fecha de Nacimiento: </div>
                    <div><?php echo $fechaNacimientoPerfil; ?></div>
                </h1>
                <h1>
                    <div>Género: </div>
                    <div><?php echo $generoPerfil; ?></div>
                </h1>
            </div>
        </section>
    </main>
</body>
</html>
<script src="../js/profile.js"></script>