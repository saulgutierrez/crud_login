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
    // Construir el comando para ejecutar el script de Python, enviando como parámetro el id del usuario logueado
    $command = escapeshellcmd("python3 ../data/knn_classifier_users.py " . escapeshellarg($user_id));
    $output = shell_exec($command);
    $jsonData = file_get_contents('results-suggestions.json');
    $results = json_decode($jsonData, true);
    echo json_encode($results);
?>