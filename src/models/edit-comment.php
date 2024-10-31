<?php
require('../../config/connection.php');

// Iniciar sesión si no se ha iniciado ya
if (!isset($_SESSION)) {
    session_start();
}

// Establecer el encabezado de contenido como JSON para asegurar que el cliente lo interprete correctamente
header('Content-Type: application/json');

// Directorio donde se almacenan las imágenes; se crea si no existe
$target_dir = "../views/uploads/";

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Inicialización de la respuesta
$response = array();

// Verificar que se han recibido los datos necesarios desde el frontend
if (isset($_POST['commentText'],$_POST['commentId'])) {
    $comment_id = $_POST['commentId'];
    $comment = $_POST['commentText'];

    $file_uploaded = false;
    $target_file = false;

    // Verificar si se ha subido un archivo y si no hay errores
    if (isset($_FILES['newCommentImage']) && $_FILES['newCommentImage']['error'] == UPLOAD_ERR_OK) {
        $filename = basename($_FILES["newCommentImage"]["name"]);
        $target_file = $target_dir . $filename;

        // Verificar tipo MIME del archivo
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_mime_type = mime_content_type($_FILES['newCommentImage']['tmp_name']);

        if (!in_array($file_mime_type, $allowed_mime_types)) {
            $response['message'] = "Tipo de archivo no permitido.";
            echo json_encode($response);
            exit;
        }

        // Mover el archivo subido a la carpeta de destino
        if (move_uploaded_file($_FILES["newCommentImage"]["tmp_name"], $target_file)) {
            $file_uploaded = true;
        } else {
            $response['message'] = "Error en la subida del archivo.";
            $response['file_error'] = $_FILES['newCommentImage']['error'];
            echo json_encode($response);
            exit;
        }
    }

    try {
        if ($file_uploaded) {
            $sql = $conn->prepare("UPDATE comentarios SET comentario = ?, foto_comentario = ? WHERE id_comentario = ?");
            $sql->bind_param('ssi', $comment, $target_file, $comment_id);
        } else {
            $sql = $conn->prepare("UPDATE comentarios SET comentario = ? WHERE id_comentario = ?");
            $sql->bind_param('si', $comment, $comment_id);
        }

        if ($sql->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Los datos se han guardado en la base de datos';
            $response['comment'] = $comment;
            if ($file_uploaded) {
                $response['message'] .= " El archivo se ha subido correctamente";
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error al ejecutar la consulta " . $sql->error;
        }
        $sql->close();
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = "Exception: " . $e->getMessage();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = "No se recibieron los datos desde el frontend.";
}

$conn->close();
echo json_encode($response);

?>
