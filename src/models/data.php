<?php
require('../../config/connection.php');

# Solucion a error: "ya habia iniciado una sesion ignorando session_start()"
if (!isset($_SESSION)) {
    session_start();
}

# Si se recibieron datos desde el frontend, los almacenamos para consulta
if (isset($_POST['user'], $_POST['password'])) {
    $user = $_POST['user'];
    $pass = $_POST['password'];

    $cryptPass = sha1($pass);
    
    $sql = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND contrasenia = ?");
    
    if ($sql) {
        $sql->bind_param("ss", $user, $cryptPass);
        
        if ($sql->execute()) {
            $result = $sql->get_result(); // Obtener el resultado de la consulta
            if ($result->num_rows > 0) {
                if (!isset($_SESSION['user'])) {
                    $_SESSION['user'] = $user;
                    echo 0;
                }
            } else {
                echo 1;
            }
        } else {
            echo 1;
        }
        $sql->close(); // Cierra la consulta preparada
    } else {
        echo "Error en la preparación de la consulta.";
    }
}
?>