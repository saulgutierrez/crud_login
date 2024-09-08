<?php
    require('../../config/connection.php');
    require('session.php');

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

    if (isset($_POST['id']) && isset($_POST['action'])) {
        $idComentario = $_POST['id'];
        $action = $_POST['action'];

        if ($action === 'like') {
            $sql = "INSERT INTO likes_comentarios (id_like_comentario, id, id_comentario, btn_text) VALUES ('', '$idUser', '$idComentario', 'Liked')";
            $result = mysqli_query($conn, $sql);

            if ($result == TRUE) {
                echo json_encode(['status' => 'liked']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error while inserting']);
            }
        } elseif ($action === 'unlike') {
            $sql = "DELETE FROM likes_comentarios WHERE id = '$idUser' AND id_comentario = '$idComentario'";
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