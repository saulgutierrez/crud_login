<?php
    require('conexion.php');
    require('data.php');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sqlGetInfo = "SELECT * FROM usuarios WHERE id = '$id'";
        $queryGetInfo = mysqli_query($conn, $sqlGetInfo);

        $sqlGetPosts = "SELECT id_post id_autor, autor_post, titulo_post, contenido_post FROM post WHERE id_autor = '$id'";
        $result = $conn->query($sqlGetPosts);

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


    } else if (isset($_SESSION['user'])) {
        $user = $_SESSION['user']; # Si el usuario esta logueado en el sistema, recibimos el nombre de usuario
        # Consultamos por el nombre de usuario en la base de datos
        $sqlGetInfo = "SELECT * FROM usuarios WHERE usuario = '$user'";
        $queryGetInfo = $conn->query($sqlGetInfo);

        $sqlGetPosts = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post FROM post WHERE autor_post = '$user'";
        $result = $conn->query($sqlGetPosts);

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

<style>
    .hidden {
        display: none;
    }
</style>

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
            <article class="group-buttons">
                <a id="btn-1" class="edit-profile-btn" href="edit-profile.php?user=<?php echo $user;?>">Editar perfil</a>
                <a id="btn-2" class="delete-profile-btn" href="delete-profile.php?user=<?php echo $user;?>">Eliminar cuenta</a>
            </article>
        </nav>
        <section>
            <div class="menu-lateral">
                <a class="info" id="info">Info</a>
                <a class="posts" id="posts">Posts</a>
                <a class="comments">Comentarios</a>
            </div>
            <div class="info-perfil" id="info-perfil">
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
            <div class="post-content">
                <?php
                $counter = 0;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $counter++;
                        $idPost = $row['id_post'];
                        $id = $row['id_autor'];
                        $autor = $row['autor_post'];
                        $titulo = $row['titulo_post'];
                        $contenido = $row['contenido_post'];
                ?>
                <div class="post-card">
                    <div class="square-menu-perfil"></div>
                    <div class="menu-opciones">
                        <a href="profile.php?user=<?php echo $user;?>?idpost=<?php echo $idPost; ?>" class="openModalBtn">Editar post</a>
                        <a href="">Eliminar post</a>
                    </div>
                    <img src="../svg/menu.svg" alt="" class="menu-icon">
                    <h2><?php echo $autor; ?></h2>
                    <h3><?php echo $titulo; ?></h3>
                    <div><?php echo $contenido; ?></div>
                </div>

                <?php
                    }
                }
                ?>
            </div>

            <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <form id="modalForm">
                            <label for="id">Id:</label>
                            <input type="text" id="id" name="id" required value="<?php echo $id; ?>">
                            <label for="id-post">Id del post:</label>
                            <input type="text" id="id-post" name="id-post" required value="<?php echo $idPost; ?>">
                            <label for="autor">Autor:</label>
                            <input type="text" id="autor" name="autor" required value="<?php echo $autor;?>">
                            <label for="titulo">Email:</label>
                            <input type="text" id="titulo" name="titulo" required value="<?php echo $titulo;?>">
                            <label for="contenido">Contenido:</label>
                            <input type="text" id="contenido" name="contenido" required value="<?php echo $contenido; ?>">
                            <button type="submit">Enviar</button>
                        </form>
                    </div>
                </div>
        </section>
    </main>

    <script src="../js/check-profile-or-user.js"></script>
    <script src="../js/profile.js"></script>
</body>
</html>