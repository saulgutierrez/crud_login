<?php
// Función para generar un código único de recuperación
function generarCodigoUnico($longitud = 8) {
    return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, $longitud);
}

// Función para generar múltiples códigos
function generarCodigosRecuperacion($cantidad = 5, $longitud = 8) {
    $codigos = [];
    for ($i = 0; $i < $cantidad; $i++) {
        $codigo = generarCodigoUnico($longitud);
        
        // Evitamos duplicados en los códigos generados
        while (in_array($codigo, $codigos)) {
            $codigo = generarCodigoUnico($longitud);
        }

        $codigos[] = $codigo;
    }
    return $codigos;
}

// Función para almacenar los códigos en la base de datos
function almacenarCodigosEnDB($userId, $codigos, $conn, $expiracionHoras = 1) {
    $expiracion = date('Y-m-d H:i:s', strtotime("+$expiracionHoras hours"));

    foreach ($codigos as $codigo) {
        $sql = "INSERT INTO codigos_recuperacion (usuario_id, codigo, usado, expiracion) VALUES (?, ?, 0, ?)";
        $stmt = $conn->prepare($sql);
        
        // Corrección en el bind_param: "iss" (i = int, s = string, s = string)
        $stmt->bind_param("iss", $userId, $codigo, $expiracion);
        
        // Ejecutar la consulta
        $stmt->execute();
        $stmt->close();
    }
}
?>