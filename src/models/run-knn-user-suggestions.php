<?php
    require('../../config/connection.php');

    if (isset($_SESSION)) {
        session_start();
    }

    // Obtener el id del usuario con sesion iniciada
    $user = $_SESSION['user'];
    $sqlGetIdUser = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sqlGetIdUser);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $id_fetch = $result->fetch_assoc();
    $user_id = $id_fetch['id'];
?>