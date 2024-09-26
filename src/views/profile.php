<?php
    require('../../config/connection.php');
    require('../models/session.php');
    require '../../public/libs/Carbon/autoload.php';

    use Carbon\Carbon;

    // Estamos viendo el perfil de otro usuario
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sqlGetInfo = "SELECT * FROM usuarios WHERE id = '$id'";
        $queryGetInfo = mysqli_query($conn, $sqlGetInfo);

        // Obtener todos los posteos del usuario
        $sqlGetPosts = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion FROM post WHERE id_autor = '$id'";
        $result = $conn->query($sqlGetPosts);

        // Consulta para obtener el numero de posteos del usuario
        $posts_count_sql = "SELECT COUNT(*) as post_count FROM post WHERE id_autor = '$id'";
        $posts_count_result = $conn->query($posts_count_sql);
        $posts_count_row = $posts_count_result->fetch_assoc();
        $post_count = $posts_count_row['post_count'];

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

            // Consulta para obtener los comentarios del usuario
            $sqlGetComments = "SELECT * FROM comentarios WHERE autor_comentario = '$nombreUsuario'";
            $queryGetComments = $conn->query($sqlGetComments);

            // Consulta para obtener el numero de comentarios del usuario
            $comments_count_sql = "SELECT COUNT(*) as comments_count FROM comentarios WHERE autor_comentario = '$nombreUsuario'";
            $comments_count_result = $conn->query($comments_count_sql);
            $comments_count_row = $comments_count_result->fetch_assoc();
            $comments_count = $comments_count_row['comments_count'];

            # Sentencias SQL para obtener los usuarios los cuales OTROS USUARIOS estan siguiendo
            $sqlGetFollowing = "SELECT * FROM siguiendo WHERE id_seguidor = '$idPerfil'";
            $queryGetFollowing = $conn->query($sqlGetFollowing);

            // Consulta para obtener el numero de "siguiendo" de otros usuarios
            $following_count_sql = "SELECT COUNT(*) as following_count FROM siguiendo WHERE id_seguidor = '$idPerfil'";
            $following_count_result = $conn->query($following_count_sql);
            $following_count_row = $following_count_result->fetch_assoc();
            $following_count = $following_count_row['following_count'];

            # Sentencias SQL para obtener los usuarios que siguen a otros usuarios (Seguidores de perfiles por id)
            $sqlGetOtherFollowers = "SELECT u.* FROM siguiendo s INNER JOIN usuarios u ON s.id_seguidor = u.id WHERE s.id_seguido = '$id'";
            $queryGetFollowers = $conn->query($sqlGetOtherFollowers);

            // Consulta para obtener el numero de seguidores
            $followers_count_sql = "SELECT COUNT(*) as followers_count FROM siguiendo s INNER JOIN usuarios u ON s.id_seguidor = u.id WHERE s.id_seguido = '$id'";
            $followers_count_result = $conn->query($followers_count_sql);
            $followers_count_row = $followers_count_result->fetch_assoc();
            $followers_count = $followers_count_row['followers_count'];

            # Sentencias SQL para obtener los posts que otros usuarios han marcado como "Like", combinamos las filas de la tabla likes con la tabla post si la condición es verdadera
            # Ayuda a mostrar, el nombre del usuario que creo el post, el titulo del post, el contenido del post, la imagen asociada, y la fecha de publicacion
            # En caso de actualizacion de los contenidos del post, o el nombre del usuario, la información se actualiza por cascada
            $sqlGetLikes = "SELECT u.usuario, u.fotografia, p.id_post, p.autor_post, p.titulo_post, p.contenido_post, p.foto_post, p.fecha_publicacion FROM likes l JOIN post p ON l.liked_id_post = p.id_post JOIN usuarios u ON p.id_autor = u.id WHERE l.liked_by = '$idPerfil'";
            $queryGetLikes = $conn->query($sqlGetLikes);

            // Consulta para obtener el numero de posts que otros usuarios han marcado como "Like"
            $likes_count_sql = "SELECT COUNT(*) as likes_count FROM likes WHERE liked_by = '$idPerfil'";
            $likes_count_result = $conn->query($likes_count_sql);
            $likes_count_row = $likes_count_result->fetch_assoc();
            $likes_count = $likes_count_row['likes_count'];

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

        // Obtenemos nuestros posteos
        $sqlGetPosts = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post FROM post WHERE autor_post = '$user'";
        $result = $conn->query($sqlGetPosts);

        // Consulta para obtener el numero de posteos
        $posts_count_sql = "SELECT COUNT(*) as post_count FROM post WHERE autor_post = '$user'";
        $posts_count_result = $conn->query($posts_count_sql);
        $posts_count_row = $posts_count_result->fetch_assoc();
        $post_count = $posts_count_row['post_count'];

        // Obtenemos nuestros comentarios
        $sqlProfileComments = "SELECT * FROM comentarios WHERE autor_comentario = '$user'";
        $queryGetComments = $conn->query($sqlProfileComments);

        // Consulta para obtener el numero de comentarios
        $comments_count_sql = "SELECT COUNT(*) as comments_count FROM comentarios WHERE autor_comentario = '$user'";
        $comments_count_result = $conn->query($comments_count_sql);
        $comments_count_row = $comments_count_result->fetch_assoc();
        $comments_count = $comments_count_row['comments_count'];

        # Sentencias SQL para obtener los usuarios los cuales YO estoy siguiendo
        $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = '$user'";
        $queryGetIdUser = $conn->query($sqlGetIdUser);

        if ($sqlGetId = mysqli_fetch_assoc($queryGetIdUser)) {
            $idUser = $sqlGetId['id'];
        }

        $sqlGetUserFollowing = "SELECT * FROM siguiendo WHERE id_seguidor = '$idUser'";
        $queryGetFollowing = $conn->query($sqlGetUserFollowing);

        # Consulta para obtener el numero de usuarios que estoy siguiendo
        $following_count_sql = "SELECT COUNT(*) as following_count FROM siguiendo WHERE id_seguidor = '$idUser'";
        $following_count_result = $conn->query($following_count_sql);
        $following_count_row = $following_count_result->fetch_assoc();
        $following_count = $following_count_row['following_count'];

        # Sentencias SQL para obtener los usuarios que "me siguen" (seguidores)
        # Hacemos un inner join para obtener los datos usuario que "me sigue" en base a su id, desde la tabla de usuarios.
        $sqlGetUserFollowers = "SELECT u.* FROM siguiendo s INNER JOIN usuarios u ON s.id_seguidor = u.id WHERE s.id_seguido = '$idUser'";
        $queryGetFollowers = $conn->query($sqlGetUserFollowers);

        // Consulta para obtener el numero de usuarios que "me siguen"
        $followers_count_sql = "SELECT COUNT(*) as followers_count FROM siguiendo s INNER JOIN usuarios u ON s.id_seguidor = u.id WHERE s.id_seguido = '$idUser'";
        $followers_count_result = $conn->query($followers_count_sql);
        $followers_count_row = $followers_count_result->fetch_assoc();
        $followers_count = $followers_count_row['followers_count'];


        # Sentencias SQL para obtener los posts que he marcado como "Like", combinamos las filas de la tabla likes con la tabla post si la condición es verdadera
        # Ayuda a mostrar, el nombre del usuario que creo el post, el titulo del post, el contenido del post, la imagen asociada, y la fecha de publicacion
        # En caso de actualizacion de los contenidos del post, o el nombre del usuario, la información se actualiza por cascada
        $sqlGetUserLikes = "SELECT u.usuario, u.fotografia, u.id, p.id_post, p.autor_post, p.titulo_post, p.contenido_post, p.foto_post, p.fecha_publicacion FROM likes l JOIN post p ON l.liked_id_post = p.id_post JOIN usuarios u ON p.id_autor = u.id WHERE l.liked_by = '$idUser'";
        $queryGetLikes = $conn->query($sqlGetUserLikes);

         // Consulta para obtener el numero de posts que he marcado como "Like"
         $likes_count_sql = "SELECT COUNT(*) as likes_count FROM likes WHERE liked_by = '$idUser'";
         $likes_count_result = $conn->query($likes_count_sql);
         $likes_count_row = $likes_count_result->fetch_assoc();
         $likes_count = $likes_count_row['likes_count'];

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
                    <a href="<?php echo $sqlGetProfile['fotografia']; ?>" data-pswp-width="800" data-pswp-height="800" class="pswp-link" onclick="return false;">
                        <img src="<?php echo $sqlGetProfile['fotografia']; ?>" alt="Foto de perfil">
                    </a>
                </figure>
                <article>
                    <h2><?php if ($nombrePerfil != '' && $apellidoPerfil != '') { echo $nombrePerfil. " ".$apellidoPerfil; } else { echo "anon"; } ?></h2>
                    <h3><?php echo $nombreUsuario; ?></h3>
                </article>
            </div>
            <article class="group-buttons">
                <a id="btn-1" class="edit-profile-btn" href="edit-profile.php?user=<?php echo $user;?>">
                    <div class="imgBox">
                        <img src="../../public/svg/edit-profile.svg" alt="">
                    </div>
                    <div>Editar perfil</div>
                </a>
                <a id="btn-2" class="delete-profile-btn" href="delete-profile.php">
                    <div class="imgBox">
                        <img src="../../public/svg/delete-profile.svg" alt="">
                    </div>
                    <div>Eliminar cuenta</div>
                </a>
                <a id="btn-3" class="follow-profile-btn" href="" data-id="<?php echo $idPerfil; ?>">Seguir usuario</a>
            </article>
        </nav>
        <section>
            <div class="menu-lateral">
                <a class="info" id="info">
                    <div class="imgBox">
                        <img src="../../public/svg/info.svg" alt="">
                    </div>
                    <div>Info</div>
                </a>
                <a class="posts" id="posts">
                    <div class="imgBox">
                        <img src="../../public/svg/new-post.svg" alt="">
                    </div>
                    <div>Posts</div>
                    <div><?php echo $post_count; ?></div>
                </a>
                <a class="comments" id="comments">
                    <div class="imgBox">
                        <img src="../../public/svg/comment.svg" alt="">
                    </div>
                    <div>Comentarios</div>
                    <div><?php echo $comments_count; ?></div>
                </a>
                <a class="followers" id="followers">
                    <div class="imgBox">
                        <img src="../../public/svg/follower.svg" alt="">
                    </div>
                    <div>Seguidores</div>
                    <div><?php echo $followers_count; ?></div>
                </a>
                <a class="following" id="following">
                    <div class="imgBox">
                        <img src="../../public/svg/following.svg" alt="">
                    </div>
                    <div>Siguiendo</div>
                    <div><?php echo $following_count; ?></div>
                </a>
                <a class="likes" id="likes">
                    <div class="imgBox">
                        <img src="../../public/svg/like.svg" alt="">
                    </div>
                    <div>Liked Posts</div> 
                    <div><?php echo $likes_count; ?></div>
                </a>
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
                        $hasImage = !empty($foto) ? 'imgBoxContent' : 'noImage';
                ?>
                <div class="post-card">
                    <div class="square-menu-perfil"></div>
                    <div class="menu-opciones" id="menu-opciones">
                        <a href="edit-post.php?id_post=<?php echo $idPost; ?>">
                            <div class="imgBox">
                                <img src="../../public/svg/new-post.svg" alt="">
                            </div>
                            <div>Editar post</div>
                        </a>
                        <a href="" class="delete-post-btn" data-id="<?php echo $idPost; ?>">
                            <div class="imgBox">
                                <img src="../../public/svg/close-circle.svg" alt="">
                            </div>
                            <div>Eliminar post</div>
                        </a>
                    </div>
                    <img src="../../public/svg/menu.svg" alt="" class="menu-icon">
                    <div class="wrapper-main-profile-items">
                        <div class="imgBox">
                            <img src="<?php echo $sqlGetProfile['fotografia'];?>" alt="">
                        </div>
                        <h2><?php echo $autor; ?></h2>
                    </div>
                    <h3><?php echo $titulo; ?></h3>
                    <div class="contenido"><?php echo $contenido; ?></div>
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
                        $hasImage = !empty($foto) ? 'imgBoxContent' : 'noImage';
                        $fecha = Carbon::parse($row['fecha_publicacion']);
                    ?>
                    <div class="post-card" onclick="window.location.href='view-post.php?id=<?php echo $id_post;?>'">
                        <div class="square-menu-perfil"></div>
                        <img src="../../public/svg/menu.svg" alt="" class="menu-icon">
                        <div class="post-card-top">
                            <div class="wrapper-main-profile-items">
                                <div class="imgBox">
                                    <img src="<?php echo $sqlGetProfile['fotografia'];?>" alt="">
                                </div>
                                <h2><?php echo $autor; ?></h2>
                            </div>
                            <div><?php echo $fecha->diffForHumans(); ?></div>
                        </div>
                        <h3><?php echo $titulo; ?></h3>
                        <div class="contenido"><?php echo $contenido; ?></div>
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
                        $hasImageComment = !empty($fotoComentario) ? 'imgBoxContent' : 'noImage';
                ?>
                <div class="comment-card">
                    <div class="square-menu-perfil-comments"></div>
                    <div class="menu-opciones-comments" id="menu-opciones-comments">
                        <a href="view-post.php?id=<?php echo $idPostItem; ?>">
                            <div class="imgBox">
                                <img src="../../public/svg/view-thread.svg" alt="">
                            </div>
                            <div>Ver hilo</div>
                        </a>
                        <a href="" class="delete-comment-btn" data-id="<?php echo $idComentarioItem; ?>">
                            <div class="imgBox">
                                <img src="../../public/svg/close-circle.svg" alt="">
                            </div>
                            <div>Eliminar comentario</div>
                        </a>
                    </div>
                    <img src="../../public/svg/menu.svg" alt="" class="menu-icon-comments">
                    <div class="wrapper-main-profile-items">
                        <div class="imgBox">
                            <img src="<?php echo $sqlGetProfile['fotografia']; ?>" alt="">
                        </div>
                        <h2><?php echo $autorComentarioItem; ?></h2>
                    </div>
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
                        $hasImageComment = !empty($fotoComentario) ? 'imgBoxContent' : 'noImage';
                        $fecha = Carbon::parse($rowComments['fecha_publicacion']);
                ?>
                <div class="comment-card" onclick="window.location.href='view-post.php?id=<?php echo $idPostItem; ?>'">
                    <div class="square-menu-perfil-comments"></div>
                    <div class="menu-opciones-comments" id="menu-opciones-comments">
                        <a href="edit-comment.php?id_comment=<?php echo $idComentarioItem;?>">Editar comentario</a>
                        <a href="" class="delete-comment-btn" data-id="<?php echo $idComment; ?>">Eliminar comentario</a>
                    </div>
                    <img src="../../public/svg/menu.svg" alt="" class="menu-icon-comments">
                    <div class="comment-card-top">
                        <div class="wrapper-main-profile-items">
                            <div class="imgBox">
                                <img src="<?php echo $sqlGetProfile['fotografia'];?>" alt="">
                            </div>
                            <h2><?php echo $autorComentarioItem; ?></h2>
                        </div>
                        <div class="fecha"><?php echo $fecha->diffForHumans(); ?></div>
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
            <div class="likes-content" id="likes-content">
                <?php
                // Ver likes de mi perfil
                $counterLikes = 0;
                if ($queryGetLikes->num_rows > 0 && !isset($_GET['id'])) {
                    while ($rowLikes = $queryGetLikes->fetch_assoc()) {
                        $counterLikes++;
                        $idLikedPost = $rowLikes['id_post'];
                        $idAutorPost = $rowLikes['id'];
                        $autorLikedPost = $rowLikes['autor_post'];
                        $tituloLikedPost = $rowLikes['titulo_post'];
                        $contenidoLikedPost = $rowLikes['contenido_post'];
                        $fotoLikedPost = $rowLikes['foto_post'];
                        $hasImageLikedPost = !empty($fotoLikedPost) ? 'imgBoxContent' : 'noImage';
                        $fechaPublicacionLikedPost = Carbon::parse($rowLikes['fecha_publicacion']);
                        $fotoPerfilLikedPost = $rowLikes['fotografia'];
                ?>
                <div class="likes-card" onclick="window.location.href ='view-post.php?id=<?php echo $idLikedPost; ?>'">
                    <div class="likes-card-top">
                        <div class="wrapper-main-profile-items">
                            <div class="imgBox">
                                <img src="<?php echo $fotoPerfilLikedPost; ?>" alt="">
                            </div>
                            <a href='profile.php?id=<?php echo $idAutorPost; ?>'" onclick="event.stopPropagation();"><?php echo $autorLikedPost; ?></a>
                        </div>
                        <div class="fecha"><?php echo $fechaPublicacionLikedPost->diffForHumans(); ?></div>
                    </div>
                    <h2><?php echo $tituloLikedPost; ?></h2>
                    <h3><?php echo $contenidoLikedPost; ?></h3>
                    <div class="<?php echo $hasImageLikedPost; ?>">
                        <img src="<?php echo $fotoLikedPost; ?>" alt="">
                    </div>
                </div>

                <?php
                    }
                ?>
                <?php
                // Ver likes de otro perfil
                } else if ($queryGetLikes->num_rows > 0 && isset($_GET['id'])) {
                    while ($rowLikes = $queryGetLikes->fetch_assoc()) {
                        $counterLikes++;
                        $idLikedPost = $rowLikes['id_post'];
                        $autorLikedPost = $rowLikes['autor_post'];
                        $tituloLikedPost = $rowLikes['titulo_post'];
                        $contenidoLikedPost = $rowLikes['contenido_post'];
                        $fotoLikedPost = $rowLikes['foto_post'];
                        $hasImageLikedPost = !empty($fotoLikedPost) ? 'imgBoxContent' : 'noImage';
                        $fechaPublicacionLikedPost = Carbon::parse($rowLikes['fecha_publicacion']);
                        $fotoPerfilLikedPost = $rowLikes['fotografia'];
                ?>
                <div class="likes-card" onclick="window.location.href='view-post.php?id=<?php echo $idLikedPost; ?>'">
                    <div class="likes-card-top">
                        <div class="wrapper-main-profile-items">
                            <div class="imgBox">
                                <img src="<?php echo $fotoPerfilLikedPost; ?>" alt="">
                            </div>
                            <h2><?php echo $autorLikedPost; ?></h2>
                        </div>
                        <div class="fecha"><?php echo $fechaPublicacionLikedPost->diffForHumans(); ?></div>
                    </div>
                    <div><?php echo $contenidoLikedPost; ?></div>
                    <div class="<?php echo $hasImageLikedPost; ?>">
                        <img src="<?php echo $fotoLikedPost; ?>" alt="">
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
        </section>
    </main>
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../helpers/check-profile-or-user.js"></script>
    <script src="../helpers/profile.js"></script>
    <script src="../controllers/delete-post.js"></script>
    <script src="../controllers/delete-comment.js"></script>
    <script src="../controllers/follow-user.js"></script>
    <script type="module" src="../helpers/view-profile-image.js"></script>
</body>
</html>