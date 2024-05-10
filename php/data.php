<?php
    require('conexion.php');

    $user = $_GET['user'];
    $pass = $_GET['password'];

    $sql = "SELECT * FROM usuarios WHERE usuario = '$user' AND contrasenia = '$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        header('Location: dashboard.php');
    } else {
        header('Location: ../index.php');
    }
?>