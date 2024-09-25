<?php
    require('../../config/connection.php');

    $id_comentario = $_POST['id_comentario'];

    $sql = "SELECT usuarios.usuario, usuarios.fotografia, likes_comentarios.id FROM likes_comentarios JOIN usuarios ON likes_comentarios.id = usuarios.id WHERE likes_comentarios.id_comentario = $id_comentario";
    $result = $conn->query($sql);

    $users = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    echo json_encode($users);

    $conn->close();
?>