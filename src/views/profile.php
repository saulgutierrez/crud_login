<?php
    require('../../config/connection.php');
    require('../models/data.php');

    // Estamos viendo el perfil de otro usuario
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sqlGetInfo = "SELECT * FROM usuarios WHERE id = '$id'";
        $queryGetInfo = mysqli_query($conn, $sqlGetInfo);

        $sqlGetPosts = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion FROM post WHERE id_autor = '$id'";
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
            $rutaFotoPorDefecto = "../../public/img/profile-default.svg";

            $sqlGetComments = "SELECT * FROM comentarios WHERE autor_comentario = '$nombreUsuario'";
            $queryGetComments = $conn->query($sqlGetComments);

            # Sentencias SQL para obtener los usuarios los cuales OTROS USUARIOS estan siguiendo
            $sqlGetFollowing = "SELECT * FROM siguiendo WHERE id_seguidor = '$idPerfil'";
            $queryGetFollowing = $conn->query($sqlGetFollowing);

            # Sentencias SQL para obtener los usuarios que siguen a otros usuarios (Seguidores de perfiles por id)
            $sqlGetOtherFollowers = "SELECT u.* FROM siguiendo s INNER JOIN usuarios u ON s.id_seguidor = u.id WHERE s.id_seguido = '$id'";
            $queryGetFollowers = $conn->query($sqlGetOtherFollowers);

        } else { # En caso de que no exista el perfil, redireccionamos a el dashboard principal
            header('Location: dashboard.php');
            exit();
        }
    // Estamos viendo mi perfil
    } else if (isset($_SESSION['user'])) {
        $user = $_SESSION['user']; # Si el usuario esta logueado en el sistema, recibimos el nombre de usuario
        # Consultamos por el nombre de usuario en la base de datos
        $sqlGetInfo = "SELECT * FROM usuarios WHERE usuario = '$user'";
        $queryGetInfo = $conn->query($sqlGetInfo);

        $sqlGetPosts = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post FROM post WHERE autor_post = '$user'";
        $result = $conn->query($sqlGetPosts);

        $sqlProfileComments = "SELECT * FROM comentarios WHERE autor_comentario = '$user'";
        $queryGetComments = $conn->query($sqlProfileComments);

        # Sentencias SQL para obtener los usuarios los cuales YO estoy siguiendo
        $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = '$user'";
        $queryGetIdUser = $conn->query($sqlGetIdUser);

        if ($sqlGetId = mysqli_fetch_assoc($queryGetIdUser)) {
            $idUser = $sqlGetId['id'];
        }

        $sqlGetUserFollowing = "SELECT * FROM siguiendo WHERE id_seguidor = '$idUser'";
        $queryGetFollowing = $conn->query($sqlGetUserFollowing);

        # Sentencias SQL para obtener los usuarios que "me siguen" (seguidores)
        # Hacemos un inner join para obtener los datos usuario que "me sigue" en base a su id, desde la tabla de usuarios.
        $sqlGetUserFollowers = "SELECT u.* FROM siguiendo s INNER JOIN usuarios u ON s.id_seguidor = u.id WHERE s.id_seguido = '$idUser';";
        $queryGetFollowers = $conn->query($sqlGetUserFollowers);

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
            $rutaFotoPorDefecto = "../../public/img/profile-default.svg";
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
    <link rel="stylesheet" href="../../public/css/profile.css">
    <title><?php if ($nombrePerfil != '' && $apellidoPerfil != '') { echo $nombrePerfil. " ".$apellidoPerfil; } else { echo "anon"; } ?> | Forum</title>
</head>

<?php include "includes/header.php"; ?>

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
                <a id="btn-3" class="follow-profile-btn" href="" data-id="<?php echo $id; ?>">Seguir usuario</a>
            </article>
        </nav>
        <section>
            <div class="menu-lateral">
                <a class="info" id="info">Info</a>
                <a class="posts" id="posts">Posts</a>
                <a class="comments" id="comments">Comentarios</a>
                <a class="followers" id="followers">Seguidores</a>
                <a class="following" id="following">Siguiendo</a>
                <a href="">Liked Posts</a>
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
                    <img src="../../public/svg/menu.svg" alt="" class="menu-icon">
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
                        $fecha = $row['fecha_publicacion'];
                    ?>
                    <div class="post-card" onclick="window.location.href='view-post.php?id=<?php echo $id_post;?>'">
                        <div class="square-menu-perfil"></div>
                        <img src="../../public/svg/menu.svg" alt="" class="menu-icon">
                        <div class="post-card-top">
                            <h2><?php echo $autor; ?></h2>
                            <div><?php echo $fecha; ?></div>
                        </div>
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
                        $fotoComentario = $rowComments['foto_comentario'];
                        $hasImageComment = !empty($fotoComentario) ? 'imgBox' : 'noImage';
                ?>
                <div class="comment-card">
                    <div class="square-menu-perfil-comments"></div>
                    <div class="menu-opciones-comments" id="menu-opciones-comments">
                        <a href="view-post.php?id=<?php echo $idPostItem; ?>""> Ver hilo</a>
                        <a href="" class="delete-comment-btn" data-id="<?php echo $idComentarioItem; ?>">Eliminar comentario</a>
                    </div>
                    <img src="../../public/svg/menu.svg" alt="" class="menu-icon-comments">
                    <h2><?php echo $autorComentarioItem; ?></h2>
                    <h3><?php echo $comentarioItem; ?></h3>
                    <div class="<?php echo $hasImageComment; ?>">
                        <img src="<?php echo $fotoComentario; ?>" alt="">
                    </div>
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
                        $fotoComentario = $rowComments['foto_comentario'];
                        $hasImageComment = !empty($fotoComentario) ? 'imgBox' : 'noImage';
                        $fecha = $rowComments['fecha_publicacion'];
                ?>
                <div class="comment-card" onclick="window.location.href='view-post.php?id=<?php echo $idPostItem; ?>'">
                    <div class="square-menu-perfil-comments"></div>
                    <div class="menu-opciones-comments" id="menu-opciones-comments">
                        <a href="edit-comment.php?id_comment=<?php echo $idComentarioItem;?>">Editar comentario</a>
                        <a href="" class="delete-comment-btn" data-id="<?php echo $idComment; ?>">Eliminar comentario</a>
                    </div>
                    <img src="../../public/svg/menu.svg" alt="" class="menu-icon-comments">
                    <div class="comment-card-top">
                        <h2><?php echo $autorComentarioItem; ?></h2>
                        <div><?php echo $fecha; ?></div>
                    </div>
                    <div><?php echo $comentarioItem; ?></div>
                    <div class="<?php echo $hasImageComment; ?>">
                        <img src="<?php echo $fotoComentario; ?>" alt="">
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
            <div class="follower-content" id="follower-content">
                <?php
                // Ver usuarios que me siguen en mi perfil (seguidores)
                $followersCounter = 0;
                $followers = []; // Inicializar el array para almacenar los registros
                $rutaFotoPorDefecto = "../../public/img/profile-default.svg";
                if ($queryGetFollowers->num_rows > 0 && !isset($_GET['id'])) {
                    while ($rowFollowers = $queryGetFollowers->fetch_assoc()) {
                        $followers[] = $rowFollowers;
                    }
                }
                ?>
                <?php if (!empty($followers)):?>
                    <?php foreach ($followers as $rowFollowers): ?>
                        <div class="follower-card">
                            <div class="imgBoxFollower">
                                <img src="<?php echo !empty($rowFollowers['fotografia']) ? $rowFollowers['fotografia'] : $rutaFotoPorDefecto; ?>" alt="">
                            </div>
                            <h2 onclick="window.location.href='profile.php?id=<?php echo $rowFollowers['id']; ?>'"><?php echo $rowFollowers['usuario']; ?></h2>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if (!isset($_GET['id'])): ?>
                        <p class="error-fetching-following">Sin seguidores</p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php
                // Ver los seguidores de otros usuarios
                $followersCounter2 = 0;
                $followers2 = []; // Inicializar el array para almacenar los registros
                $rutaFotoPorDefecto = "../../public/img/profile-default.svg";
                if ($queryGetFollowers->num_rows > 0 && isset($_GET['id'])) {
                    while ($rowOtherFollowers = $queryGetFollowers->fetch_assoc()) {
                        $followers2[] = $rowOtherFollowers;
                    }
                }
                ?>
                <?php if (!empty($followers2)):?>
                    <?php foreach ($followers2 as $rowOtherFollowers): ?>
                        <div class="follower-card">
                            <div class="imgBoxFollower">
                                <img src="<?php echo !empty($rowOtherFollowers['fotografia']) ? $rowOtherFollowers['fotografia'] : $rutaFotoPorDefecto; ?>" alt="">
                            </div>
                            <?php
                                $redirectUrl = 'profile.php?';

                                if (isset($_SESSION['user'])) {
                                    $user = $_SESSION['user'];
                                    $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = '$user'";
                                    $queryGetIdUser = $conn->query($sqlGetIdUser);
                                    # Evaluamos, en caso de que el id que seguimos corresponda al id que esta logueado,
                                    # es decir, nuestro propio perfil, nos envie hacia la pantalla de gestion de nuestro
                                    # perfil, en lugar de dar lo opcion de "seguirnos a nosotros mismos".
                                    if ($sqlGetId = mysqli_fetch_assoc($queryGetIdUser)) {
                                        $idUser = $sqlGetId['id'];
                                        if ($rowOtherFollowers['id'] == $idUser) {
                                            $redirectUrl .= 'user=' . $idUser;
                                        } else {
                                            $redirectUrl .= 'id=' . $rowOtherFollowers['id'];
                                        }
                                    }
                                }
                            ?>
                            <h2 onclick="window.location.href='<?php echo $redirectUrl; ?>'">
                                <?php echo $rowOtherFollowers['usuario']; ?>
                            </h2>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if (isset($_GET['id'])): ?>
                        <p class="error-fetching-following">Sin seguidores</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="following-content" id="following-content">
                <?php
                // Ver usuarios que sigo en mi perfil
                $followingCounter = 0;
                $followings = []; // Inicializar el array para almacenar los registros
                if ($queryGetFollowing->num_rows > 0 && !isset($_GET['id'])) {
                    while ($rowFollowing = $queryGetFollowing->fetch_assoc()) {
                        $followings[] = $rowFollowing; // Almacenar cada registro en el array
                    }
                }
                ?>
                <?php if (!empty($followings)): ?>
                    <?php foreach ($followings as $rowFollowing): ?>
                        <div class="following-card">
                            <div class="imgBoxFollowing">
                                <img src="<?php echo !empty($rowFollowing['foto_seguido']) ? $rowFollowing['foto_seguido'] : $rutaFotoPorDefecto; ?>" alt="">
                            </div>
                            <h2 onclick="window.location.href='profile.php?id=<?php echo $rowFollowing['id_seguido']; ?>'"><?php echo $rowFollowing['nombre_usuario_seguido']; ?></h2>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if (!isset($_GET['id'])): ?>
                        <p class="error-fetching-following">No sigues ninguna cuenta</p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php
                // Ver usuarios que otros usuarios siguen en sus perfiles
                $followingCounter2 = 0;
                $followings2 = []; // Inicializar el array para almacenar los registros
                if ($queryGetFollowing->num_rows > 0 && isset($_GET['id'])) {
                    while($rowFollowing2 = $queryGetFollowing->fetch_assoc()) {
                        $followings2[] = $rowFollowing2;
                    }
                }
                ?>
                <?php if (!empty($followings2)): ?>
                    <?php foreach ($followings2 as $rowFollowing2): ?>
                        <div class="following-card">
                            <div class="imgBoxFollowing">
                                <img src="<?php echo $rowFollowing2['foto_seguido']?>" alt="">
                            </div>
                            <?php
                            // Generar la URL de redirección
                            $redirectUrl = 'profile.php?';

                            if (isset($_SESSION['user'])) {
                                $user = $_SESSION['user'];
                                $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = '$user'";
                                $queryGetIdUser = $conn->query($sqlGetIdUser);
                                # Evaluamos, en caso de que el id que seguimos corresponda al id que esta logueado,
                                # es decir, nuestro propio perfil, nos envie hacia la pantalla de gestion de nuestro
                                # perfil, en lugar de dar lo opcion de "seguirnos a nosotros mismos".
                                if ($sqlGetId = mysqli_fetch_assoc($queryGetIdUser)) {
                                    $idUser = $sqlGetId['id'];
                                    if ($rowFollowing2['id_seguido'] == $idUser) {
                                        $redirectUrl .= 'user=' . $idUser;
                                    } else {
                                        $redirectUrl .= 'id=' . $rowFollowing2['id_seguido'];
                                    }
                                }
                            }
                            ?>
                            <h2 onclick="window.location.href='<?php echo $redirectUrl; ?>'">
                                <?php echo $rowFollowing2['nombre_usuario_seguido']; ?>
                            </h2>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if (isset($_GET['id'])): ?>
                        <p class="error-fetching-following">Este usuario no sigue a nadie</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../helpers/check-profile-or-user.js"></script>
    <script src="../helpers/profile.js"></script>
    <script src="../controllers/delete-post.js"></script>
    <script src="../controllers/delete-comment.js"></script>
    <script src="../controllers/follow-user.js"></script>
</body>
</html>