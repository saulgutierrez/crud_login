<?php
    $servidor = 'mysql.railway.internal';
    $usuario = 'root';
    $password = 'UmqSJjAexYIJikmoUWtJDNCmAuPEsqGN';
    $nombre_db = 'railway';

    // Conexion
    $conn = new mysqli($servidor, $usuario, $password, $nombre_db);

    if ($conn->connect_error) {
        die("Error de conexion: ".$conn->connect_error);
    }
?>