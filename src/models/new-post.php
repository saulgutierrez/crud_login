<?php
require('../../config/connection.php');
require('notifications.php');

// Solución a error: "ya había iniciado una sesión ignorando session_start()"
if (!isset($_SESSION)) {
    session_start();
}

// Directorio donde se almacenan las imagenes, si no existe, se crea
$target_dir = "../views/uploads/";

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Si se recibieron datos desde el frontend, los almacenamos para consulta
if (isset($_POST['id_user'], $_POST['user'], $_POST['post_title'], $_POST['post_content'], $_POST['post_time'], $_POST['category'])) {
    $idPerfil = $_POST['id_user'];
    $username = $_POST['user'];
    $postTitle = $_POST['post_title'];
    $postContent = $_POST['post_content'];
    $postTime = $_POST['post_time'];
    $category = $_POST['category'];
    

    $file_uploaded = false; // Bandera para verificar si el post será con fotografia o no
    $target_file = null;

    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        // Create random filename
        $file_extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $random_filename = uniqid('img_', true) . '.' . $file_extension;
        $target_file = $target_dir . $random_filename;

        // Verificar tipo MIME
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_mime_type = mime_content_type($_FILES['file']['tmp_name']);

        if (!in_array($file_mime_type, $allowed_mime_types)) {
            $response['message'] = "Tipo de archivo no permitido.";
            echo json_encode($response);
            exit;
        }

        // Mover archivo subido a la carpeta de destino
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_uploaded = true; // Si se subio un fichero, cambiamos la bandera a true
        } else {
            $response['message'] = "Error en la subida del archivo.";
            $response['file_error'] = $_FILES['file']['error'];
            echo json_encode($response);
            exit;
        }
    }

    // Preparamos la consulta y vinculamos los parámetros correctamente
    if ($file_uploaded) { // Si se subio una fotografia, tomamos su path, y lo almacenamos en la base de datos
        $sql = $conn->prepare("INSERT INTO post (id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post, fecha_publicacion, id_categoria) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param('issssss', $idPerfil, $username, $postTitle, $postContent, $target_file, $postTime, $category);
    } else { // En caso contrario, guardamos una cadena vacía
        $sql = $conn->prepare("INSERT INTO post (id_post, id_autor, autor_post, titulo_post, contenido_post, fecha_publicacion, id_categoria) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param('isssss', $idPerfil, $username, $postTitle, $postContent, $postTime, $category);
    }

    if ($sql->execute()) {
        $response['status'] = 'success';
        $response['message'] = "Los datos se han guardado en la base de datos.";
        if ($file_uploaded) {
            $response['message'] .= " El archivo se ha subido correctamente.";
        }
        // Llamada a la funcion para crear la notificacion despues de insertar el post
        $post_id = $conn->insert_id; // Obtener el id del post recien insertado
        notificar_nuevo_post($idPerfil, $post_id);
    } else {
        $response['message'] = "Error al ejecutar la consulta: " . $sql->error;
    }
    $sql->close(); // Cerramos el statement después de la ejecución
} else {
    $response['message'] = "No se recibieron los datos desde el frontend.";
}

$conn->close(); // Cerramos la conexión a la base de datos
echo json_encode($response);
?>