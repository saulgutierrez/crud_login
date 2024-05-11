<?php
    # Cuando el usuario de click en cerrar sesion, destruimos las variables de sesion
    # para negar el acceso, y redireccionamos a la pantalla de login
    session_start();
    session_destroy();
    header('Location: ../index.php');
    exit();
?>