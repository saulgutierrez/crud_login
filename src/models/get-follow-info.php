<?php
require('../../config/connection.php');

if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Consulta para obtener el número de seguidores
    $seguidores_sql = "SELECT COUNT(*) as seguidores FROM siguiendo WHERE id_seguido = ?";
    $stmt = $conn->prepare($seguidores_sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $seguidores = $result->fetch_assoc()['seguidores'];

    // Consulta para obtener el número de seguidos
    $seguidos_sql = "SELECT COUNT(*) as seguidos FROM siguiendo WHERE id_seguidor = ?";
    $stmt = $conn->prepare($seguidos_sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $seguidos = $result->fetch_assoc()['seguidos'];

    // Consulta para obtener el número de posteos
    $posts_sql = "SELECT COUNT(*) as posts_count FROM post WHERE id_autor = ?";
    $stmt = $conn->prepare($posts_sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts_count = $result->fetch_assoc()['posts_count'];

    // Retornar los datos en formato JSON
    echo json_encode([
        'seguidores' => $seguidores,
        'seguidos' => $seguidos,
        'posts_count' => $posts_count
    ]);
}

// Cerrar conexión
$conn->close();
?>