<?php
    $servidor = getenv("MYSQLHOST");
    $usuario = getenv("MYSQLUSER");
    $password = getenv("MYSQLPASSWORD");
    $nombre_db = getenv("MYSQLDATABASE");
    $puerto = getenv("MYSQLPORT");

    // Conexion
    $conn = new mysqli($servidor, $usuario, $password, $nombre_db, $puerto);

    if ($conn->connect_error) {
        die("Error de conexion: ".$conn->connect_error);
    }
?>