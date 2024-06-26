<?php
    require('connection.php');
    require('data.php');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sqlGetInfo = "SELECT * FROM usuarios WHERE id = '$id'";
        $queryGetInfo = mysqli_query($conn, $sqlGetInfo);

        $sqlGetPosts = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post FROM post WHERE id_autor = '$id'";
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

            $sqlGetComments = "SELECT * FROM comentarios WHERE autor_comentario = '$nombreUsuario'";
            $queryGetComments = $conn->query($sqlGetComments);

        } else { # En caso de que no exista el perfil, redireccionamos a el dashboard principal
            header('Location: dashboard.php');
            exit();
        }

    } else if (isset($_SESSION['user'])) {
        $user = $_SESSION['user']; # Si el usuario esta logueado en el sistema, recibimos el nombre de usuario
        # Consultamos por el nombre de usuario en la base de datos
        $sqlGetInfo = "SELECT * FROM usuarios WHERE usuario = '$user'";
        $queryGetInfo = $conn->query($sqlGetInfo);

        $sqlGetPosts = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post FROM post WHERE autor_post = '$user'";
        $result = $conn->query($sqlGetPosts);

        $sqlProfileComments = "SELECT * FROM comentarios WHERE autor_comentario = '$user'";
        $queryGetComments = $conn->query($sqlProfileComments);

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
                <a id="btn-2" class="delete-profile-btn" href="delete-profile.php">Eliminar cuenta</a>
            </article>
        </nav>
        <section>
            <div class="menu-lateral">
                <a class="info" id="info">Info</a>
                <a class="posts" id="posts">Posts</a>
                <a class="comments" id="comments">Comentarios</a>
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
            <div class="post-content" id="post-content">
                <?php
                $counter = 0;
                // Ver posteos de mi perfil
                if ($result->num_rows > 0 && !isset($_GET['id'])) {
                    while ($row = $result->fetch_assoc()) {
                        $counter++;
                        $idPost = $row['id_post'];
                        $id = $row['id_autor'];
                        $autor = $row['autor_post'];
                        $titulo = $row['titulo_post'];
                        $contenido = $row['contenido_post'];
                        $foto = $row['foto_post'];
                        $hasImage = !empty($foto) ? 'imgBox' : 'noImage';
                ?>
                <div class="post-card">
                    <div class="square-menu-perfil"></div>
                    <div class="menu-opciones" id="menu-opciones">
                        <a href="edit-post.php?id_post=<?php echo $idPost; ?>">Editar post</a>
                        <a href="" class="delete-post-btn" data-id="<?php echo $idPost; ?>">Eliminar post</a>
                    </div>
                    <img src="../svg/menu.svg" alt="" class="menu-icon">
                    <h2><?php echo $autor; ?></h2>
                    <h3><?php echo $titulo; ?></h3>
                    <div><?php echo $contenido; ?></div>
                    <div class="<?php echo $hasImage; ?>">
                        <img src="<?php echo $foto; ?>" alt="">
                    </div>
                </div>

                <?php
                    }
                ?>
                <?php
                // Ver posteos de otros usuarios
                } else if ($result->num_rows > 0 && isset($_GET['id'])) {
                    $counter = 0;
                    while ($row = $result->fetch_assoc()) {
                        $counter++;
                        $id_post = $row['id_post'];
                        $id = $row['id_autor'];
                        $autor = $row['autor_post'];
                        $titulo = $row['titulo_post'];
                        $contenido = $row['contenido_post'];
                        $foto = $row['foto_post'];
                        $hasImage = !empty($foto) ? 'imgBox' : 'noImage';
                    ?>
                    <div class="post-card" onclick="window.location.href='view-post.php?id=<?php echo $id_post;?>'">
                        <div class="square-menu-perfil"></div>
                        <img src="../svg/menu.svg" alt="" class="menu-icon">
                        <h2><?php echo $autor; ?></h2>
                        <h3><?php echo $titulo; ?></h3>
                        <div><?php echo $contenido; ?></div>
                        <div class="<?php echo $hasImage; ?>">
                            <img src="<?php echo $foto; ?>" alt="">
                        </div>
                    </div>
                <?php
                    }
                }
                ?>
            </div>
            <div class="comment-content" id="comment-content">
                <?php
                // Ver comentarios de mi perfil
                $counterComments = 0;
                if ($queryGetComments->num_rows > 0 && !isset($_GET['id'])) {
                    while ($rowComments = $queryGetComments->fetch_assoc()) {
                        $counterComments++;
                        $idPostItem = $rowComments['id_post'];
                        $idAutorItem = $rowComments['id_autor'];
                        $idComentarioItem = $rowComments['id_comentario'];
                        $autorComentarioItem = $rowComments['autor_comentario'];
                        $comentarioItem = $rowComments['comentario'];
                ?>
                <div class="comment-card">
                    <div class="square-menu-perfil-comments"></div>
                    <div class="menu-opciones-comments" id="menu-opciones-comments">
                        <a href="view-post.php?id=<?php echo $idPostItem; ?>""> Ver hilo</a>
                        <a href="" class="delete-comment-btn" data-id="<?php echo $idComentarioItem; ?>">Eliminar comentario</a>
                    </div>
                    <img src="../svg/menu.svg" alt="" class="menu-icon-comments">
                    <h2><?php echo $autorComentarioItem; ?></h2>
                    <h3><?php echo $comentarioItem; ?></h3>
                </div>

                <?php
                    }
                ?>
                <?php
                // Ver comentarios de otro perfil
                } else if ($queryGetComments->num_rows > 0 && isset($_GET['id'])) {
                    while ($rowComments = $queryGetComments->fetch_assoc()) {
                        $counterComments++;
                        $idPostItem = $rowComments['id_post'];
                        $idAutorItem = $rowComments['id_autor'];
                        $idComentarioItem = $rowComments['id_comentario'];
                        $autorComentarioItem = $rowComments['autor_comentario'];
                        $comentarioItem = $rowComments['comentario'];
                ?>
                <div class="comment-card" onclick="window.location.href='view-post.php?id=<?php echo $idPostItem; ?>'">
                    <div class="square-menu-perfil-comments"></div>
                    <div class="menu-opciones-comments" id="menu-opciones-comments">
                        <a href="edit-comment.php?id_comment=<?php echo $idComentarioItem;?>">Editar comentario</a>
                        <a href="" class="delete-comment-btn" data-id="<?php echo $idComment; ?>">Eliminar comentario</a>
                    </div>
                    <img src="../svg/menu.svg" alt="" class="menu-icon-comments">
                    <h2><?php echo $autorComentarioItem; ?></h2>
                    <div><?php echo $comentarioItem; ?></div>
                </div>
                <?php
                    }
                }
                ?>

            </div>
        </section>
    </main>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/check-profile-or-user.js"></script>
    <script src="../js/profile.js"></script>
    <script src="../js/delete-post.js"></script>
    <script src="../js/delete-comment.js"></script>
</body>
</html>