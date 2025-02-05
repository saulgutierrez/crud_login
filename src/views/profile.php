<?php
    require('../../config/connection.php');
    require('../models/session.php');
    require '../../public/libs/Carbon/autoload.php';
    include '../models/get-block-state.php';

    use Carbon\Carbon;
    Carbon::setLocale('es');
    date_default_timezone_set('America/Mexico_City'); 

    if (isset($_SESSION['user'])) {
        $username = $_SESSION['user'];
        // Consulta para obtener el ID del usuario en base al nombre de usuario
        // Obtengo el el del usuario autenticado
        $userId = "SELECT id FROM usuarios WHERE usuario = '$username'";
        $getUser = $conn->query($userId);

        if ($getUser->num_rows > 0) {
            while ($row = $getUser->fetch_assoc()) {
                $idOfMyProfile = $row['id'];
            }
        }
    }

    // Estamos viendo el perfil de otro usuario
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $idOfViewerProfile = $_GET['id'];

        $sqlGetInfo = "SELECT * FROM usuarios WHERE id = '$id'";
        $queryGetInfo = mysqli_query($conn, $sqlGetInfo);

        // Obtener todos los posteos del usuario
        $sqlGetPosts = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion FROM post WHERE id_autor = '$id' ORDER BY fecha_publicacion DESC";
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
            $sqlGetComments = "SELECT * FROM comentarios WHERE autor_comentario = '$nombreUsuario' ORDER BY fecha_publicacion DESC";
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

            // Obtenemos las fotos de otros usuarios
            $sqlGetPhotos = "SELECT p.foto_post AS fotografia FROM post p WHERE p.id_autor = '$idPerfil' AND p.foto_post <> '' UNION ALL SELECT c.foto_comentario AS fotografia FROM comentarios c WHERE c.id_autor_comentario = '$idPerfil' AND c.foto_comentario <> '';";
            $queryGetPhotos = $conn->query($sqlGetPhotos);

            // Consulta para obtener el numero de fotos que otros usuarios han subido en sus posteos y comentarios
            $photos_count_sql = "SELECT COUNT(*) as photos_count FROM (SELECT p.id_autor FROM post p WHERE p.id_autor = '$idPerfil' AND p.foto_post <> '' UNION ALL SELECT c.id_autor_comentario FROM comentarios c WHERE c.id_autor_comentario = '$idPerfil' AND c.foto_comentario <> '') AS total;";
            $photos_count_result = $conn->query($photos_count_sql);
            $photos_count_row = $photos_count_result->fetch_assoc();
            $photos_count = $photos_count_row['photos_count'];

        } else { # En caso de que no exista el perfil, redireccionamos a el dashboard principal
            header('Location: dashboard.php');
            exit();
        }
    // Estamos viendo mi perfil
    } else if (isset($_SESSION['user'])) {
        $user = $_SESSION['user']; # Si el usuario esta logueado en el sistema, recibimos el nombre de usuario
        $userId = "SELECT id FROM usuarios WHERE usuario = '$user'";
        $getUser = $conn->query($userId);

        if ($getUser->num_rows > 0) {
            while ($row = $getUser->fetch_assoc()) {
                $idOfMyProfile = $row['id'];
            }
        }
        # Consultamos por el nombre de usuario en la base de datos
        $sqlGetInfo = "SELECT * FROM usuarios WHERE usuario = '$user'";
        $queryGetInfo = $conn->query($sqlGetInfo);

        // Obtenemos nuestros posteos
        $sqlGetPosts = "SELECT id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post FROM post WHERE autor_post = '$user' ORDER BY fecha_publicacion DESC";
        $result = $conn->query($sqlGetPosts);

        // Consulta para obtener el numero de posteos
        $posts_count_sql = "SELECT COUNT(*) as post_count FROM post WHERE autor_post = '$user'";
        $posts_count_result = $conn->query($posts_count_sql);
        $posts_count_row = $posts_count_result->fetch_assoc();
        $post_count = $posts_count_row['post_count'];

        // Obtenemos nuestros comentarios
        $sqlProfileComments = "SELECT * FROM comentarios WHERE autor_comentario = '$user' ORDER BY fecha_publicacion DESC";
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

        // Obtenemos nuestras fotos
        $sqlGetUserPhotos = "SELECT p.foto_post AS fotografia FROM post p WHERE p.id_autor = '$idUser' AND p.foto_post <> '' UNION ALL SELECT c.foto_comentario AS fotografia FROM comentarios c WHERE c.id_autor_comentario = '$idUser' AND c.foto_comentario <> '';";
        $queryGetPhotos = $conn->query($sqlGetUserPhotos);

        // Consulta para obtener el numero de fotos que el usuario ha subido en sus posteos y comentarios
        $photos_count_sql = "SELECT COUNT(*) as photos_count FROM (SELECT p.id_autor FROM post p WHERE p.id_autor = '$idUser' AND p.foto_post <> '' UNION ALL SELECT c.id_autor_comentario FROM comentarios c WHERE c.id_autor_comentario = '$idUser' AND c.foto_comentario <> '') AS total;";
        $photos_count_result = $conn->query($photos_count_sql);
        $photos_count_row = $photos_count_result->fetch_assoc();
        $photos_count = $photos_count_row['photos_count'];

        // Consulta para obtener los usuarios que hemos bloqueado
        $sqlGetUserBlocks = "SELECT usuarios.id, usuarios.usuario, usuarios.fotografia FROM user_blocks JOIN usuarios ON user_blocks.blocked_id = usuarios.id WHERE user_blocks.blocker_id = ?";
        $sqlPrepareQuery = $conn->prepare($sqlGetUserBlocks);
        $sqlPrepareQuery->bind_param("i", $idUser);
        $sqlPrepareQuery->execute();
        $resultGetUserBlocks = $sqlPrepareQuery->get_result();

        // Consulta para obtener el numero de bloqueos
        $blocks_count_sql = "SELECT COUNT(*) as blocks_count FROM user_blocks WHERE blocker_id = '$idUser'";
        $blocks_count_result = $conn->query($blocks_count_sql);
        $blocks_count_row = $blocks_count_result->fetch_assoc();
        $blocks_count = $blocks_count_row['blocks_count'];


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
    <link href="../../public/svg/forum-icon-black.svg" rel="icon" media="(prefers-color-scheme: light)">
    <link href="../../public/svg/forum-icon-white.svg" rel="icon" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="../../public/css/profile.css">
    <title><?php if ($nombrePerfil != '' && $apellidoPerfil != '') { echo $nombrePerfil. " ".$apellidoPerfil; } else { echo "anon"; } ?> | Forum</title>
</head>

<?php include "includes/header.php"; ?>

<body class="main-screen">
    <main>
        <nav>
            <div>
                <figure>
                    <a href="<?php echo $sqlGetProfile['fotografia']; ?>" data-pswp-width="500" data-pswp-height="500" class="pswp-link" onclick="return false;">
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
                <a id="btn-3" class="follow-profile-btn" href="" data-id="<?php echo $idPerfil; ?>">
                    <div class="imgBox">
                        <img src="../../public/svg/follow-user.svg" alt="">
                    </div>
                    <div class="follow-text">Seguir</div>
                </a>
                <a id="btn-4" class="block-profile-btn" href="" data-id="<?php echo $idPerfil; ?>">
                    <div class="imgBox">
                        <img src="../../public/svg/block-user.svg" alt="">
                    </div>
                    <div class="blocked-text">Bloquear</div>
                </a>
            </article>
        </nav>
        <section>
            <div class="menu-lateral">
                <a class="info" id="info" href="#">
                    <div class="imgBox">
                        <img src="../../public/svg/info.svg" alt="">
                    </div>
                    <div>Info</div>
                </a>
                <a class="posts" id="posts" href="#post-content">
                    <div class="imgBox">
                        <img src="../../public/svg/new-post.svg" alt="">
                    </div>
                    <div>Posts</div>
                    <div><?php echo $post_count; ?></div>
                </a>
                <a class="comments" id="comments" href="#comment-content">
                    <div class="imgBox">
                        <img src="../../public/svg/comment.svg" alt="">
                    </div>
                    <div>Comentarios</div>
                    <div><?php echo $comments_count; ?></div>
                </a>
                <a class="followers" id="followers" href="#followers-content">
                    <div class="imgBox">
                        <img src="../../public/svg/follower.svg" alt="">
                    </div>
                    <div>Seguidores</div>
                    <div><?php echo $followers_count; ?></div>
                </a>
                <a class="following" id="following" href="#following-content">
                    <div class="imgBox">
                        <img src="../../public/svg/following.svg" alt="">
                    </div>
                    <div>Siguiendo</div>
                    <div><?php echo $following_count; ?></div>
                </a>
                <a class="likes" id="likes" href="#likes-content">
                    <div class="imgBox">
                        <img src="../../public/svg/like.svg" alt="">
                    </div>
                    <div>Liked Posts</div> 
                    <div><?php echo $likes_count; ?></div>
                </a>
                <a class="photos" id="photos" href="#photos-content">
                    <div class="imgBox">
                        <img src="../../public/svg/photos.svg" alt="">
                    </div>
                    <div>Fotos</div>
                    <div><?php echo $photos_count; ?></div>
                </a>
                <a class="blocks" id="blocks" href="#blocks-content">
                    <div class="imgBox">
                        <img src="../../public/svg/block-user.svg" alt="">
                    </div>
                    <div>Bloqueos</div>
                    <div><?php echo $blocks_count; ?></div>
                </a>
            </div>
            <div class="info-perfil" id="info-perfil">
                    <h1>
                        <div>Nombre: </div>
                        <div> <?php echo ($nombrePerfil != '' && $apellidoPerfil != '') ? $nombrePerfil . " " . $apellidoPerfil : "anon"; ?> </div>
                    </h1>
                    <h1>
                        <div>Correo: </div>
                        <div><?php echo $correoPerfil; ?></div>
                    </h1>
                    <h1>
                        <div>Teléfono: </div>
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

                        // Consulta para obtener el numero de likes de este post
                        $likes_sql = "SELECT COUNT(*) as like_count FROM likes WHERE liked_id_post = '$idPost'";
                        $likes_result = $conn->query($likes_sql);
                        $likes_row = $likes_result->fetch_assoc();
                        $like_count = $likes_row['like_count'];

                        // Consulta para obtener el numero de comentarios de cada post
                        $comments_sql = "SELECT COUNT(*) as comment_count FROM comentarios WHERE id_post = '$idPost'";
                        $comments_result = $conn->query($comments_sql);
                        $comments_row = $comments_result->fetch_assoc();
                        $comments_count = $comments_row['comment_count'];
                ?>
                <div class="post-card">
                    <div class="square-menu-perfil"></div>
                    <div class="menu-opciones" id="menu-opciones">
                        <a href="view-post.php?id=<?php echo $idPost; ?>">
                            <div class="imgBox">
                                <img src="../../public/svg/view-thread.svg" alt="">
                            </div>
                            <div>Ver hilo</div>
                        </a>
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
                    <div class="my-gallery w-100">
                        <div class="<?php echo $hasImage; ?>">
                            <a href="<?php echo $foto; ?>" data-pswp-width="500" data-pswp-height="500" class="pswp-link" data-pswp-index="1" onclick="return false;">
                                <img src="<?php echo $foto; ?>" alt="">
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="stats-container">
                        <div class="stats-container-child">
                        <a href="#" class="like-count" data-id=" <?php echo $idPost; ?>"> <?php echo $like_count; ?> </a>
                            <div class="comment-count"><?php echo $comments_count; ?></div>
                        </div>
                        <a class="like-button" data-id="<?php echo $idPost; ?>">Like</a>
                        <div class="imgBoxLike">
                            <img src="../../public/svg/heart.svg" alt="">
                        </div>
                        <div class="imgBoxComment">
                            <img src="../../public/svg/comment.svg" alt="">
                        </div>
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
                        $fechaFormateada = $fecha->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] h:mm a');

                        // Consulta para obtener el numero de likes de este post
                        $likes_sql = "SELECT COUNT(*) as like_count FROM likes WHERE liked_id_post = '$id_post'";
                        $likes_result = $conn->query($likes_sql);
                        $likes_row = $likes_result->fetch_assoc();
                        $like_count = $likes_row['like_count'];

                        // Consulta para obtener el numero de comentarios de cada post
                        $comments_sql = "SELECT COUNT(*) as comment_count FROM comentarios WHERE id_post = '$id_post'";
                        $comments_result = $conn->query($comments_sql);
                        $comments_row = $comments_result->fetch_assoc();
                        $comments_count = $comments_row['comment_count'];
                    ?>
                    <div class="post-card">
                        <div class="square-menu-perfil"></div>
                        <img src="../../public/svg/menu.svg" alt="" class="menu-icon">
                        <div class="post-card-top">
                            <div class="wrapper-main-profile-items">
                                <div class="imgBox">
                                    <img src="<?php echo $sqlGetProfile['fotografia'];?>" alt="">
                                </div>
                                <h2><?php echo $autor; ?></h2>
                            </div>
                            <div class="fecha" style="width: auto;"><?php echo $fecha->diffForHumans(); ?></div>
                            <div class="fecha-formateada" style="width: auto;"><?php echo $fechaFormateada; ?></div>
                        </div>
                        <h3><?php echo $titulo; ?></h3>
                        <div class="contenido"><?php echo $contenido; ?></div>
                        <div class="my-gallery w-100">
                            <div class="<?php echo $hasImage; ?>">
                                <a href="<?php echo $foto;?>" data-pswp-width="500" data-pswp-height="500" class="pswp-link" data-pswp-index="1" onclick="return false;">
                                    <img src="<?php echo $foto; ?>" alt="">
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="stats-container">
                            <div class="stats-container-child">
                            <a onclick="#" class="like-count like-count-hover" data-id="<?php echo $id_post; ?>"> <?php echo $like_count; ?> </a>
                                <a class="comment-count comment-count-hover" href="view-post.php?id=<?php echo $id_post;?>"><?php echo $comments_count; ?></a>
                            </div>
                            <a class="like-button" data-id="<?php echo $id_post; ?>">Like</a>
                            <div class="imgBoxLike">
                                <img src="../../public/svg/heart.svg" alt="">
                            </div>
                            <div class="imgBoxComment">
                                <img src="../../public/svg/comment.svg" alt="">
                            </div>
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

                        // Consulta para obtener el numero de likes de este comentario
                        $likes_comment_sql = "SELECT COUNT(*) as like_comment_count FROM likes_comentarios WHERE id_comentario = '$idComentarioItem'";
                        $likes_comment_result = $conn->query($likes_comment_sql);
                        $likes_comment_row = $likes_comment_result->fetch_assoc();
                        $like_comment_count = $likes_comment_row['like_comment_count'];
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
                        <a href="" class="openModalLink" data-id="<?php echo $idComentarioItem; ?>" data-comment="<?php echo $comentarioItem; ?>" data-image="<?php echo $fotoComentario; ?>">
                            <div class="imgBox">
                                <img src="../../public/svg/edit-comment.svg" alt="">
                            </div>
                            <div>Editar comentario</div>
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
                    <h3 id="comentarioItem<?php echo $idComentarioItem; ?>" class="comment-item"><?php echo $comentarioItem; ?></h3>
                    <div class="<?php echo $hasImageComment; ?>" id="imageBoxContent<?php echo $idComentarioItem;?>">
                        <img id="commentImage<?php echo $idComentarioItem; ?>" class="foto-comentario" src="<?php echo $fotoComentario; ?>" alt="">
                    </div>
                    <hr>
                    <div class="stats-container">
                        <div class="stats-container-child">
                            <a href="#" class="like-comment-count" data-id="<?php echo $idComentarioItem; ?>"> <?php echo $like_comment_count; ?> </a>
                        </div>
                        <a class="like-comment-button" data-id="<?php echo $idComentarioItem; ?>">Like</a>
                        <div class="imgBoxLike">
                            <img src="../../public/svg/heart.svg" alt="">
                        </div>
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
                        $formato = 'd/m/Y, g:i:s a';
                        $fecha = Carbon::createFromFormat($formato, $rowComments['fecha_publicacion']);
                        $fechaFormateada = $fecha->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] h:mm a');

                        // Consulta para obtener el numero de likes de este comentario
                        $likes_comment_sql = "SELECT COUNT(*) as like_comment_count FROM likes_comentarios WHERE id_comentario = '$idComentarioItem'";
                        $likes_comment_result = $conn->query($likes_comment_sql);
                        $likes_comment_row = $likes_comment_result->fetch_assoc();
                        $like_comment_count = $likes_comment_row['like_comment_count'];
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
                        <div class="fecha" style="width: auto;"><?php echo $fecha->diffForHumans(); ?></div>
                        <div class="fecha-formateada" style="width: auto;"><?php echo $fechaFormateada; ?></div>
                    </div>
                    <div class="comment-item"><?php echo $comentarioItem; ?></div>
                    <div class="<?php echo $hasImageComment; ?>" id="imageBoxContent<?php echo $idComentarioItem;?>">
                        <img id="commentImage<?php echo $idComentarioItem; ?>" class="foto-comentario" src="<?php echo $fotoComentario; ?>" alt="">
                    </div>
                    <hr>
                    <div class="stats-container">
                        <div class="stats-container-child">
                            <a href="#" class="like-comment-count" data-id="<?php echo $idComentarioItem; ?>"> <?php echo $like_comment_count; ?></a>
                        </div>
                        <a class="like-comment-button" data-id="<?php echo $idComentarioItem; ?>">Like</a>
                        <div class="imgBoxLike">
                            <img src="../../public/svg/heart.svg" alt="">
                        </div>
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
                            <div class="follower-username-content">
                                <div class="imgBoxFollower">
                                    <img src="<?php echo !empty($rowFollowers['fotografia']) ? $rowFollowers['fotografia'] : $rutaFotoPorDefecto; ?>" alt="">
                                </div>
                                <h2 onclick="window.location.href='profile.php?id=<?php echo $rowFollowers['id']; ?>'"><?php echo $rowFollowers['usuario']; ?></h2>
                            </div>
                            <a href="" class="follower-profile-btn-list" data-id="<?php echo $rowFollowers['id']; ?>">
                                <div class="imgBox">
                                    <img src="../../public/svg/follow-user.svg" alt="">
                                </div>
                                <div class="follower-text-profile-btn-list">Seguir</div>
                            </a>
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
            if (isset($_GET['id'])) {
                if (!empty($followers2)):
                    foreach ($followers2 as $rowOtherFollowers): ?>
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
                                    
                                    // Obtiene el id del usuario logueado y verifica si es el mismo del seguidor
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
                    <?php endforeach;
                else: 
                    if (isset($_GET['id'])): ?>
                        <p class="error-fetching-following">Sin seguidores</p>
                    <?php endif;
                endif;
            }
            ?>

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
                            <div class="following-username-content">
                                <div class="imgBoxFollowing">
                                    <img src="<?php echo !empty($rowFollowing['foto_seguido']) ? $rowFollowing['foto_seguido'] : $rutaFotoPorDefecto; ?>" alt="">
                                </div>
                                <h2 onclick="window.location.href='profile.php?id=<?php echo $rowFollowing['id_seguido']; ?>'"><?php echo $rowFollowing['nombre_usuario_seguido']; ?></h2>
                            </div>
                            <a href="" class="follow-profile-btn-list"data-id="<?php echo $rowFollowing['id_seguido']; ?>">
                                <div class="imgBox">
                                    <img src="../../public/svg/follow-user.svg" alt="">
                                </div>
                                <div class="follow-text-profile-btn-list">Seguir</div>
                            </a>
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

                // Verifica si el usuario actual está bloqueado para ver este perfil
                if (isset($_GET['id'])) {
                    if (!empty($followings2)): ?>
                        <?php foreach ($followings2 as $rowFollowing2): ?>
                            <div class="following-card">
                                <div class="imgBoxFollowing">
                                    <img src="<?php echo $rowFollowing2['foto_seguido'] ?>" alt="">
                                </div>
                                <?php
                                // Generar la URL de redirección
                                $redirectUrl = 'profile.php?';

                                if (isset($_SESSION['user'])) {
                                    $user = $_SESSION['user'];
                                    $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = '$user'";
                                    $queryGetIdUser = $conn->query($sqlGetIdUser);

                                    // Verifica si el seguidor es el usuario logueado para ajustar el enlace
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
                    <?php endif;
                }
                ?>

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
                        $fechaFormateada = $fechaPublicacionLikedPost->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] h:mm a');
                        $fotoPerfilLikedPost = $rowLikes['fotografia'];

                        // Consulta para obtener el numero de likes de este post
                        $likes_sql_liked_posts = "SELECT COUNT(*) as like_count_liked_posts FROM likes WHERE liked_id_post = '$idLikedPost'";
                        $likes_result_liked_posts = $conn->query($likes_sql_liked_posts);
                        $likes_row_liked_posts = $likes_result_liked_posts->fetch_assoc();
                        $like_count_liked_posts = $likes_row_liked_posts['like_count_liked_posts'];

                        // Consulta para obtener el numero de comentarios de cada post
                        $comments_sql_liked_posts = "SELECT COUNT(*) as comment_count_liked_posts FROM comentarios WHERE id_post = '$idLikedPost'";
                        $comments_result_liked_posts = $conn->query($comments_sql_liked_posts);
                        $comments_row_liked_posts = $comments_result_liked_posts->fetch_assoc();
                        $comments_count_liked_posts = $comments_row_liked_posts['comment_count_liked_posts'];
                ?>
                <div class="likes-card" onclick="window.location.href ='view-post.php?id=<?php echo $idLikedPost; ?>'">
                    <div class="likes-card-top">
                        <div class="wrapper-main-profile-items">
                            <div class="imgBox">
                                <img src="<?php echo $fotoPerfilLikedPost; ?>" alt="">
                            </div>
                            <?php
                                $redirectUrl = 'profile.php?';
                                if (isset($_SESSION['user'])) {
                                    $user = $_SESSION['user'];
                                    $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = '$user'";
                                    $queryGetIdUser = $conn->query($sqlGetIdUser);

                                    // Obtiene el id del usuario logueado y verifica si es el mismo del usuario que dio like (autolike)
                                    if ($sqlGetId = mysqli_fetch_assoc($queryGetIdUser)) {
                                        $idUser = $sqlGetId['id'];
                                        if ($idAutorPost == $idUser) {
                                            $redirectUrl .= 'user=' . $autorLikedPost;
                                        } else {
                                            $redirectUrl .= 'id=' . $idAutorPost;
                                        }
                                    }
                                }
                            ?>
                            <a href='<?php echo $redirectUrl; ?>'" onclick="event.stopPropagation();"><?php echo $autorLikedPost; ?></a>
                        </div>
                        <div class="fecha" style="width: auto;"><?php echo $fechaPublicacionLikedPost->diffForHumans(); ?></div>
                        <div class="fecha-formateada" style="width: auto;"><?php echo $fechaFormateada; ?></div>
                    </div>
                    <h2><?php echo $tituloLikedPost; ?></h2>
                    <h3><?php echo $contenidoLikedPost; ?></h3>
                    <div class="<?php echo $hasImageLikedPost; ?>">
                        <img src="<?php echo $fotoLikedPost; ?>" alt="">
                    </div>
                    <hr>
                    <div class="stats-container">
                        <div class="stats-container-child">
                            <a class="like-count like-count-hover" data-id="<?php echo $idLikedPost; ?>"> <?php echo $like_count_liked_posts; ?></a>
                            <div class="comment-count mt-5"><?php echo $comments_count_liked_posts; ?></div>
                        </div>
                        <a class="like-button" data-id="<?php echo $idLikedPost; ?>">Like</a>
                        <div class="imgBoxLike">
                            <img src="../../public/svg/heart.svg" alt="">
                        </div>
                        <div class="imgBoxComment">
                            <img src="../../public/svg/comment.svg" alt="">
                        </div>
                    </div>
                </div>

                <?php
                    }
                ?>
                <?php
                // Ver likes de otro perfil
                } else if (isset($_GET['id'])) {
                        if ($queryGetLikes->num_rows > 0) {
                            while ($rowLikes = $queryGetLikes->fetch_assoc()) {
                                $counterLikes++;
                                $idLikedPost = $rowLikes['id_post'];
                                $autorLikedPost = $rowLikes['autor_post'];
                                $tituloLikedPost = $rowLikes['titulo_post'];
                                $contenidoLikedPost = $rowLikes['contenido_post'];
                                $fotoLikedPost = $rowLikes['foto_post'];
                                $hasImageLikedPost = !empty($fotoLikedPost) ? 'imgBoxContent' : 'noImage';
                                $fechaPublicacionLikedPost = Carbon::parse($rowLikes['fecha_publicacion']);
                                $fechaFormateada = $fechaPublicacionLikedPost->isoFormat('dddd, D [de] MMMM [de] YYYY [a las] h:mm a');
                                $fotoPerfilLikedPost = $rowLikes['fotografia'];

                                // Consulta para obtener el numero de likes de este post
                                $likes_sql_liked_posts = "SELECT COUNT(*) as like_count_liked_posts FROM likes WHERE liked_id_post = '$idLikedPost'";
                                $likes_result_liked_posts = $conn->query($likes_sql_liked_posts);
                                $likes_row_liked_posts = $likes_result_liked_posts->fetch_assoc();
                                $like_count_liked_posts = $likes_row_liked_posts['like_count_liked_posts'];

                                // Consulta para obtener el numero de comentarios de cada post
                                $comments_sql_liked_posts = "SELECT COUNT(*) as comment_count_liked_posts FROM comentarios WHERE id_post = '$idLikedPost'";
                                $comments_result_liked_posts = $conn->query($comments_sql_liked_posts);
                                $comments_row_liked_posts = $comments_result_liked_posts->fetch_assoc();
                                $comments_count_liked_posts = $comments_row_liked_posts['comment_count_liked_posts'];
                ?>
                                <div class="likes-card" onclick="window.location.href='view-post.php?id=<?php echo $idLikedPost; ?>'">
                                    <div class="likes-card-top">
                                        <div class="wrapper-main-profile-items">
                                            <div class="imgBox">
                                                <img src="<?php echo $fotoPerfilLikedPost; ?>" alt="">
                                            </div>
                                            <h2><?php echo $autorLikedPost; ?></h2>
                                        </div>
                                        <div class="fecha" style="width: auto;"><?php echo $fechaPublicacionLikedPost->diffForHumans(); ?></div>
                                        <div class="fecha-formateada" style="width: auto;"><?php echo $fechaFormateada; ?></div>
                                    </div>
                                    <h2><?php echo $tituloLikedPost; ?></h2>
                                    <h3><?php echo $contenidoLikedPost; ?></h3>
                                    <div class="<?php echo $hasImageLikedPost; ?>">
                                        <img src="<?php echo $fotoLikedPost; ?>" alt="">
                                    </div>
                                    <hr>
                                    <div class="stats-container">
                                        <div class="stats-container-child">
                                            <a class="like-count like-count-hover" data-id="<?php echo $idLikedPost; ?>"> <?php echo $like_count_liked_posts; ?></a>
                                            <div class="comment-count mt-5"><?php echo $comments_count_liked_posts; ?></div>
                                        </div>
                                        <a class="like-button" data-id="<?php echo $idLikedPost; ?>">Like</a>
                                        <div class="imgBoxLike">
                                            <img src="../../public/svg/heart.svg" alt="">
                                        </div>
                                        <div class="imgBoxComment">
                                            <img src="../../public/svg/comment.svg" alt="">
                                        </div>
                                    </div>
                                </div>
                <?php
                            }
                        } else {
                            echo "<p>Este usuario no ha dado 'like' a ninguna publicación.</p>";
                        }
                }
                ?>
            </div>
            <div class="photos-content" id="photos-content">
                <?php
                // Ver fotos de mi perfil
                $counterPhotos = 0;
                if ($queryGetPhotos->num_rows > 0 && !isset($_GET['id'])) {
                    echo '<div class="my-gallery mt-40">';
                    while ($rowPhotos = $queryGetPhotos->fetch_assoc()) {
                        echo '<figure class="photo-content">';
                        echo '<a href="'.$rowPhotos['fotografia'].'" data-pswp-width="500" data-pswp-height="500" class="pswp-link" data-pswp-index="'.$counterPhotos.'" onclick="return false;">';
                        echo '<img src="'.$rowPhotos['fotografia'].'" alt="Photo '.$counterPhotos.'">';
                        echo '</a>';
                        echo '</figure>';
                        $counterPhotos++;
                    }
                    echo '</div>';
                    // Ver fotos de otros usuarios
                } else if (isset($_GET['id'])) {
                    // Verificar si el usuario actual está bloqueado para ver las fotos de este perfil
                        if ($queryGetPhotos->num_rows > 0) {
                            echo '<div class="my-gallery">';
                            $counterPhotos = 0;
                            while ($rowPhotos = $queryGetPhotos->fetch_assoc()) {
                                echo '<figure class="photo-content">';
                                echo '<a href="'.$rowPhotos['fotografia'].'" data-pswp-width="500" data-pswp-height="500" class="pswp-link" data-pswp-index="'.$counterPhotos.'" onclick="return false;">';
                                echo '<img src="'.$rowPhotos['fotografia'].'" alt="Photo '.$counterPhotos.'">';
                                echo '</a>';
                                echo '</figure>';
                                $counterPhotos++;
                            }
                            echo '</div>';
                        } else {
                            echo "<p>Este usuario no tiene fotos para mostrar.</p>";
                        }
                }
                ?>
                <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="pswp__bg"></div>
                    <div class="pswp__scroll-wrap">
                        <div class="pswp__container">
                            <div class="pswp__item"></div>
                            <div class="pswp__item"></div>
                            <div class="pswp__item"></div>
                        </div>
                        <div class="pswp__ui pswp__ui--hidden">
                            <div class="pswp__top-bar">
                                <button class="pswp__button pswp__button--close" title="Cerrar (Esc)"></button>
                                <button class="pswp__button pswp__button--share" title="Compartir"></button>
                                <button class="pswp__button pswp__button--fs" title="Pantalla completa"></button>
                                <button class="pswp__button pswp__button--zoom" title="Zoom"></button>
                                <div class="pswp__counter"></div>
                            </div>

                            <button class="pswp__button pswp__button--arrow--left" title="Anterior (flecha izquierda)"></button>
                            <button class="pswp__button pswp__button--arrow--right" title="Siguiente (flecha derecha)"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="blocks-content" id="blocks-content">
                <?php
                    if (!isset($_GET['id'])) {
                        if ($resultGetUserBlocks->num_rows > 0) {
                            while ($row = $resultGetUserBlocks->fetch_assoc()) {
                                echo '<div class="blocks-card">';
                                echo '<div class="blocks-username-content">';
                                echo '<div class="imgBoxBlocksCard">';
                                echo '<img src="'.$row['fotografia'].'">';
                                echo '</div>';
                                echo '<h2 onclick="window.location.href=\'profile.php?id='.$row['id']. '\'">';
                                echo $row['usuario'];
                                echo '</h2>';
                                echo '</div>';
                                echo '<a class="blocked-profile-btn-list" data-id="'.$row['id'].'">';
                                echo '<div class="imgBox">';
                                echo '<img src="../../public/svg/block-user.svg" alt>';
                                echo '</div>';
                                echo '<div class="blocked-text-profile-btn-list">';
                                echo 'Bloquear';
                                echo '</div>';
                                echo '</a>';
                                echo '</div>';

                            }
                        }
                    }
                ?>
            </div>
        </section>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form>
                    <input type="hidden" name="commentIdInput" id="commentIdInput" value="">
                    <div class="contentInput">
                        <input type="text" name="commentInput" id="commentInput" value="" placeholder="Comentar">
                        <div class="image-upload">
                            <label for="newCommentImage">
                                <img src="../../public/svg/image-icon.svg" alt="">
                            </label>
                            <input type="file" id="newCommentImage" name="newCommentImage" accept="image/*">
                        </div>
                    </div>
                    <div class="imgBoxCommentUpdate">
                        <img id="commentImage" src="" alt="Imagen del comentario" style="max-width: 100%; display: none;">
                        <img src="../../public/svg/close-circle.svg" alt="" class="close-icon" id="close-icon">
                    </div>
                    <button id="saveComment">
                        <div class="imgBoxSaveComment">
                            <img src="../../public/svg/save.svg" alt="">
                        </div>
                        <div class="update-label">Actualizar</div>
                    </button>
                </form>
            </div>
        </div>

        <div id="likeModal" class="modal-likes modal-likes-hidden">
            <div class="modal-content-likes">
                <div class="modal-header">
                    <h2>Likes</h2>
                </div>
                <hr>
                <div class="modal-body">
                    <ul id="likesList" class="list-group"></ul>
                </div>
            </div>
        </div>
    </main>
    <script src="../ui/check-profile-or-user.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/photoswipe.min.css" integrity="sha512-LFWtdAXHQuwUGH9cImO9blA3a3GfQNkpF2uRlhaOpSbDevNyK1rmAjs13mtpjvWyi+flP7zYWboqY+8Mkd42xA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/umd/photoswipe-lightbox.umd.min.js" integrity="sha512-D16CBrIrVF48W0Ou0ca3D65JFo/HaEAjTugBXeWS/JH+1KNu54ZOtHPccxJ7PQ44rTItUT6DSI6xNL+U34SuuQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.4/umd/photoswipe.umd.min.js" integrity="sha512-BXwwGU7zCXVgpT2jpXnTbioT9q1Byf7NEXVxovTZPlNvelL2I/4LjOaoiB2a19L+g5za8RbkoJFH4fMPQcjFFw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/photoswipe@5.3.8/dist/photoswipe-lightbox.min.js"></script>
    <script src="https://unpkg.com/photoswipe@5.3.8/dist/photoswipe.min.js"></script>
    <script src="../../public/js/jquery-3.7.1.min.js"></script>
    <script src="../ui/profile.js"></script>
    <script src="../handlers/delete-post.js"></script>
    <script src="../handlers/delete-comment.js"></script>
    <script src="../handlers/follow-user.js"></script>
    <script src="../handlers/check-follow-button-status.js"></script>
    <script src="../handlers/check-follower-button-status.js"></script>
    <script src="../ui/view-full-date.js"></script>
    <script type="module" src="../ui/view-profile-image.js"></script>
    <script type="module" src="../ui/photo-gallery.js"></script>
    <script src="../ui/edit-comment-viewer.js"></script>
    <script src="../handlers/edit-comment.js"></script>
    <script src="../handlers/likes-profile.js"></script>
    <script src ="../handlers/likes-comment-profile.js"></script>
    <script src="../handlers/check-block-button-status.js"></script>
    <script>
        // Este id sirve para evaluar el id de cada usuario de la lista de likes en los posteos,
        // para evaluar si se trata de un "autolike"
        let authUserId = "<?php echo $idOfMyProfile; ?>";
    </script>
    <script>
        // Estos id's sirven para evaluar el estado del bloqueo, y ocultar los datos de perfil
        // de forma dinamica
        const myProfileId = "<?php echo $idOfMyProfile; ?>";
        const otherUserId = "<?php echo $_GET['id']; ?>";
    </script>
    <script src="../handlers/block-user.js"></script>
    <style>
        .pswp--one-slide .pswp__button--arrow {
            display: flex;
            z-index: 10000000000000;
        }

        .pswp__button--zoom {
            display: flex;
            z-index: 10000000000000;
        }

        .pswp--one-slide .pswp__counter {
            display: flex;
            z-index: 10000000000000;
        }
    </style>
</body>
</html>