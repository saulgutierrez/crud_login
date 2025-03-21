<?php
    require('../../config/connection.php');

    if (!isset($_SESSION)) {
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

    // Obtener posteos a los que el usuario ha dado like
    function obtenerPosteosLikeados($user_id, $conn) {
        $sql = "SELECT p.id_post, p.id_autor, p.autor_post, p.titulo_post, p.contenido_post FROM likes l JOIN post p ON l.liked_id_post = p.id_post WHERE l.liked_by = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener todos los posteos y convertirlos en vectores
    function obtenerCaracteristicasPosteos($conn) {
        $sql = "SELECT p.id_post, p.id_autor, p.autor_post, p.titulo_post, p.contenido_post, u.fotografia, u.id FROM post p JOIN usuarios u ON p.id_autor = u.id";
        $result = $conn->query($sql);

        $posteos = [];
        while ($row = $result->fetch_assoc()) {
            $palabras = explode(" ", strtolower($row['contenido_post'])); // Extraer palabras clave
            $posteos[$row['id_post']] = [
                'id_post' => $row['id_post'],
                'id_autor' => $row['id_autor'],
                'autor_post' => $row['autor_post'],
                'titulo_post' => $row['titulo_post'],
                'contenido_post' => $row['contenido_post'],
                'fotografia' => $row['fotografia'],
                'id' => $row['id'],
                'palabras' => array_count_values($palabras) // Frecuencia de palabras
            ];
        }
        return $posteos;
    }

    // Calcular la Similitud Coseno entre posteos
    function calcularSimilitudCoseno($vectorA, $vectorB) {
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        foreach ($vectorA as $palabra => $frecuencia) {
            if (isset($vectorB[$palabra])) {
                $dotProduct += $frecuencia * $vectorB[$palabra];
            }
            $normA += pow($frecuencia, 2);
        }

        foreach ($vectorB as $frecuencia) {
            $normB += pow($frecuencia, 2);
        }

        return ($normA && $normB) ? $dotProduct / (sqrt($normA) * sqrt($normB)) : 0;
    }

    // Ejecutar KNN para encontrar posteos similares
    function obtenerSugerenciasKNN($usuario_id, $k, $conn) {
        $posteos_likeados = obtenerPosteosLikeados($usuario_id, $conn);
        $caracteristicas_posteos = obtenerCaracteristicasPosteos($conn);
        
        $similitudes = [];

        foreach ($caracteristicas_posteos as $id_post => $vector_post) {
            if (in_array($id_post, array_column($posteos_likeados, 'id_post'))) {
                continue; // No recomendamos posteos ya likeados
            }

            $sim_total = 0;

            foreach ($posteos_likeados as $post_likeado) {
                $sim_total += calcularSimilitudCoseno($vector_post['palabras'], $caracteristicas_posteos[$post_likeado['id_post']]['palabras']);
            }

            $similitudes[$id_post] = $sim_total;
        }

        arsort($similitudes); // Ordenar de mayor a menor similitud

        // Obtener los IDs k posteos más similares
        $post_ids = array_slice(array_keys($similitudes), 0, $k);

        if (empty($post_ids)) {
            return [];
        }

        // Obtener datos de los posteos recomendados
        $placeholders = implode(",", array_fill(0, count($post_ids), "?"));
        $sql = "SELECT p.id_post, p.id_autor, p.autor_post, p.titulo_post, p.contenido_post, u.fotografia, u.id FROM post p JOIN usuarios u ON p.id_autor = u.id WHERE id_post IN ($placeholders)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat("i", count($post_ids)), ...$post_ids);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    $k = 5;
    $sugerencias = obtenerSugerenciasKNN($user_id, $k, $conn);
    echo json_encode($sugerencias);

?>