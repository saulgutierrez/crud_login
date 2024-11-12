<?php
require('../../config/connection.php');

header('Content-Type: application/json');

// Verificar que se haya recibido el ID del comentario
if (isset($_GET['commentId'])) {
    $commentId = intval($_GET['commentId']); // Asegúrate de que sea un número entero

    // Consulta para obtener el comentario actualizado por ID
    $sql = "SELECT comentario, foto_comentario FROM comentarios WHERE id_comentario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el comentario
    if ($result->num_rows > 0) {
        $comment = $result->fetch_assoc();

        // Formatear la respuesta con el texto y la ruta de la imagen
        $response = [
            'status' => 'success',
            'commentText' => $comment['comentario'],
            'commentImage' => $comment['foto_comentario']
        ];
    } else {
        // Si no se encuentra el comentario, enviar un mensaje de error
        $response = [
            'status' => 'error',
            'message' => 'Comentario no encontrado'
        ];
    }

    $stmt->close();
} else {
    // Si no se recibe un ID válido
    $response = [
        'status' => 'error',
        'message' => 'ID de comentario no proporcionado'
    ];
}

$conn->close();
echo json_encode($response);