<?php
    require('conexion.php');

    # Solucion a error: "ya habia iniciado una sesion ignorando session_start()"
    if (!isset($_SESSION)) {
        session_start();
    }
    
    $target_dir = "uploads/";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    # Si se recibieron datos desde el frontend, los almacenamos para consulta
    if (isset($_POST['id_user'], $_POST['post_title'], $_POST['post_content'])) {
        $idPerfil = $_POST['id_user'];
        $username = $_POST['user'];
        $postTitle = $_POST['post_title'];
        $postContent = $_POST['post_content'];

        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $filename = basename($_FILES["file"]["name"]);
            $target_file = $target_dir . $filename;

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
                $sql = $conn->prepare("INSERT INTO post (id_post, id_autor, autor_post, titulo_post, contenido_post, foto_post) VALUES (?, ?, ?, ?, ?, ?)");
                $sql->bind_param('', $idPerfil, $username, $postTitle, $postContent, $target_file);
                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = "El archivo se ha subido y los datos se han guardado en la base de datos.";
                } else {
                    $response['message'] = "Error al ejecutar la consulta: " . $stmt->error;
                }
                $conn->close();
            } else {
                $response['message'] = "Error en la subida del archivo.";
                $response['file_error'] = $_FILES['file']['error'];
            }
        } else {
            $response['message'] = "Error en la subida del archivo.";
            $response['file_error'] = $_FILES['file']['error'];
        }
    } else {
        echo 4; # No se recibieron los datos desde el frontend
        $conn->close();
    }
    $conn->close();

    echo json_encode($response);
?>