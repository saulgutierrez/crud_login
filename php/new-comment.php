<?php
require('connection.php'); // Incluye el archivo de conexión a la base de datos

if (!isset($_SESSION)) {
    session_start();
}

header('Content-Type: application/json');

$response = array();

// Directorio donde se guardará el archivo
$target_dir = "uploads/";

// Asegúrate de que el directorio exista y tenga permisos adecuados
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true); // Crea el directorio si no existe
}

// Verifica si se recibió el campo de comentario desde el frontend
if (isset($_POST['comment-input'])) {
    $idPost = $_POST['id-post'];
    $idAutorPost = $_POST['id-autor-post'];
    $idAutorComentario = $_POST['id-autor-comentario'];
    $autorComentario = $_POST['autor-comentario'];
    $comentario = $_POST['comment-input'];

    $file_uploaded = false;

    // Verifica si se subió un archivo y si no hubo errores
    if (isset($_FILES['file-input']) && $_FILES['file-input']['error'] == UPLOAD_ERR_OK) {
        $filename = basename($_FILES["file-input"]["name"]);
        $target_file = $target_dir . $filename;

        // Verifica el tipo MIME del archivo
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_mime_type = mime_content_type($_FILES['file-input']['tmp_name']);

        if (!in_array($file_mime_type, $allowed_mime_types)) {
            $response['status'] = 'error';
            $response['message'] = "Tipo de archivo no permitido.";
            echo json_encode($response);
            exit;
        }

        // Intenta mover el archivo al directorio de destino
        if (move_uploaded_file($_FILES["file-input"]["tmp_name"], $target_file)) {
            $file_uploaded = true;
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error en la subida del archivo.";
            echo json_encode($response);
            exit;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "Error en la subida del archivo." .$_FILES['file-input'];
        echo json_encode($response);
    }

    try {
        $conn->begin_transaction();

        // Prepara la consulta SQL dependiendo de si se subió un archivo o no
        if ($file_uploaded) {
            $sql = $conn->prepare("INSERT INTO comentarios (id_post, id_autor, id_autor_comentario, autor_comentario, comentario, foto_comentario) VALUES (?, ?, ?, ?, ?, ?)");
            $sql->bind_param("iiisss", $idPost, $idAutorPost, $idAutorComentario, $autorComentario, $comentario, $target_file);
        } else {
            $sql = $conn->prepare("INSERT INTO comentarios (id_post, id_autor, id_autor_comentario, autor_comentario, comentario) VALUES (?, ?, ?, ?, ?)");
            $sql->bind_param("iiiss", $idPost, $idAutorPost, $idAutorComentario, $autorComentario, $comentario);
        }

        // Ejecuta la consulta SQL
        if (!$sql->execute()) {
            throw new Exception("Error al actualizar comentarios: " . $sql->error);
        }

        // Confirma la transacción si todo fue exitoso
        $conn->commit();

        // Prepara la respuesta
        $response['status'] = "success";
        $response['message'] = "Los datos se han guardado en la base de datos.";
        if ($file_uploaded) {
            $response['message'] .= " El archivo se ha subido correctamente.";
        }
    } catch (Exception $e) {
        // Revierte la transacción en caso de error
        $conn->rollback();
        $response['status'] = 'error';
        $response['message'] = "Excepción: " . $e->getMessage();
        error_log($e->getMessage());
    } finally {
        // Cierra la conexión a la base de datos
        $conn->close();
    }
} else {
    // Si no se recibieron los datos desde el frontend
    $response['status'] = 'error';
    $response['message'] = "No se recibieron los datos desde el frontend.";
}

// Devuelve la respuesta como JSON
echo json_encode($response);
exit;
?>
