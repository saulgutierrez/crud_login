<?php
    require('conexion.php');

    # Solucion a error: "ya habia iniciado una sesion ignorando session_start()"
    if (!isset($_SESSION)) {
        session_start();
    }
    
    # Si se recibieron datos desde el frontend, los almacenamos para consulta
    if (isset($_POST['user'], $_POST['password'])) {
        $user = $_POST['user'];
        $pass = $_POST['password'];

        $cryptPass = sha1($pass);
        # Consultamos si los datos coinciden en la base de datos
        $sql = "SELECT * FROM usuarios WHERE usuario = '$user' AND contrasenia = '$cryptPass'";
        $result = $conn->query($sql);
        # Si existe alguna coincidencia, creamos una variable de sesion
        # para identificar al usuario y mantenerlo dentro del sistema
        if ($result->num_rows > 0) {
            if (!isset($_SESSION['user'])) {
                $_SESSION['user'] = $user;
                echo 0;
            }
        } else {
            echo 1;
        }   
    }
?>