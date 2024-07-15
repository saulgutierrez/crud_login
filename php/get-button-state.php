<?php
    require('connection.php');
    require('data.php');

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-Type: application/json; charset=UTF-8");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT btn_text FROM siguiendo WHERE id_seguido = $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $isFollowing = $result->num_rows > 0;

        // $btnText = $row['btn_text'];

        echo json_encode([
            "status" => $isFollowing ? 'following' : 'not_following'
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid ID"
        ]);
    }
?>