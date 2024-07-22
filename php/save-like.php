<?php
    require('connection.php');
    require('data.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    $username = $_SESSION['user'];

    $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = '$username'";
    $result = $conn->query($sqlGetIdUser);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $idUser = $row['id'];
        }
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['id'])) {

            $idPost = $_POST['id'];

            $sqlGetPostDetails = "SELECT autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion FROM post WHERE id_post = '$idPost'";
            $getPostDetailsQuery = $conn->query($sqlGetPostDetails);

            if ($getPostDetailsQuery->num_rows > 0) {
                while($rowGetPostDetails = $getPostDetailsQuery->fetch_assoc()) {
                    $autorLikedPost = $rowGetPostDetails['autor_post'];
                    $tituloLikedPost = $rowGetPostDetails['titulo_post'];
                    $contenidoLikedPost = $rowGetPostDetails['contenido_post'];
                    $fotoLikedPost = $rowGetPostDetails['foto_post'];
                    $fechaPublicacionLikedPost = $rowGetPostDetails['fecha_publicacion'];
                }
            }

            $sql = "INSERT INTO likes (liked_by, liked_id_post, autor_liked_post, titulo_liked_post, contenido_liked_post, foto_liked_post, fecha_publicacion_liked_post, btn_text) VALUES('$idUser','$idPost','$autorLikedPost','$tituloLikedPost','$contenidoLikedPost','$fotoLikedPost','$fechaPublicacionLikedPost','Liked')";
            $insertLikeQuery = mysqli_query($conn, $sql);

            if ($insertLikeQuery == TRUE) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo "ID no recibido";
        }
    } else {
        echo "Error al procesar la información";
    }
?>