<?php
    $servidor = 'localhost';
    $usuario = 'root';
    $password = '';
    $nombre_db = 'crud_login';

    // Conexion
    $conn = new mysqli($servidor, $usuario, $password, $nombre_db);

    if ($conn->connect_error) {
        die("Error de conexion: ".$conn->connect_error);
    }
?>