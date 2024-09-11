<?php
require('../../config/connection.php');

$id_post = $_POST['id_post'];

$sql = "SELECT usuarios.usuario, usuarios.fotografia, liked_by FROM likes JOIN usuarios ON likes.liked_by = usuarios.id WHERE likes.liked_id_post = $id_post;";
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
