<?php
    require('../../config/connection.php');
    require('data.php');
    require('notifications.php');

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

    if (isset($_POST['id']) && isset($_POST['action']) && isset($_SESSION['user'])) {
        $id = $_POST['id'];
        $action = $_POST['action'];
        $user = $_SESSION['user'];

        $sqlGetPostDetails = "SELECT autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion FROM post WHERE id_post = '$id'";
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

        if ($action === 'like') {
            $sql = "INSERT INTO likes (liked_by, liked_id_post, autor_liked_post, titulo_liked_post, contenido_liked_post, foto_liked_post, fecha_publicacion_liked_post, btn_text) VALUES('$idUser','$id','$autorLikedPost','$tituloLikedPost','$contenidoLikedPost','$fotoLikedPost','$fechaPublicacionLikedPost','Liked')";
            $result = mysqli_query($conn, $sql);

            if ($result == TRUE) {
                notificar_like($id, $idUser);
                echo json_encode(['status' => 'liked']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error while inserting']);
            }
        } elseif ($action === 'unlike') {
            $sql = "DELETE FROM likes WHERE liked_by = '$idUser' AND liked_id_post = '$id'";
            $result = mysqli_query($conn, $sql);

            if ($result == TRUE) {
                echo json_encode(['status' => 'unliked']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error while deleting']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data received']);
    }
?>