<?php
    require('connection.php');
    require('data.php');

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user']; # Si el usuario esta logueado en el sistema, recibimos el nombre de usuario
        # Consultamos por el nombre de usuario en la base de datos
        $sqlGetInfo = "SELECT * FROM usuarios WHERE usuario = '$user'";
        $queryGetInfo = mysqli_query($conn, $sqlGetInfo);

        # Recuperamos la informacion asociada a la consulta
        if ($sqlGetProfile = mysqli_fetch_assoc($queryGetInfo)) {
            $idPerfil = $sqlGetProfile['id'];
            $nombreUsuario = $sqlGetProfile['usuario'];
            $nombrePerfil = $sqlGetProfile['nombre'];
            $apellidoPerfil = $sqlGetProfile['apellido'];
            $correoPerfil = $sqlGetProfile['correo'];
            $telefonoPerfil = $sqlGetProfile['telefono'];
            $fechaNacimientoPerfil = $sqlGetProfile['fecha_nacimiento'];
            $generoPerfil = $sqlGetProfile['genero'];
            $foto = $sqlGetProfile['fotografia'];
            $rutaFotoPorDefecto = "../img/profile-default.svg";
        } else { # En caso de que no exista el perfil, redireccionamos a el dashboard principal
            header('Location: dashboard.php');
            exit();
        }
    } else {
        header('Location: dashboard.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil | Forum</title>
    <link rel="stylesheet" href="../css/edit-profile.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/edit-profile.js"></script>
</head>

<?php include "../includes/header.php"; ?>

<body>
    <form method="POST" id="editProfileForm">
        <input type="hidden" id="id" name="id" value="<?php echo $idPerfil; ?>">
        <label for="user">Usuario</label>
        <input type="text" id="user" name="user" value="<?php echo $nombreUsuario; ?>">
        <label for="pass">Contraseña</label>
        <input type="password" id="password" name="password">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombrePerfil; ?>">
        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" value="<?php echo $apellidoPerfil; ?>">
        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo" value="<?php echo $correoPerfil; ?>">
        <label for="telefono">Telefono</label>
        <input type="number" id="telefono" name="telefono" value="<?php echo $telefonoPerfil; ?>">
        <label for="fechanacimiento">Fecha de nacimiento</label>
        <input type="date" id="fechanacimiento" name="fechanacimiento" value="<?php echo $fechaNacimientoPerfil; ?>">
        <label for="genero">Genero</label>
        <div class="custom-select" style="width: 250px;">
            <select name="genero" id="genero" class="genero">
                <option value="">Seleccione</option>
                <option value="Femenino">Femenino</option>
                <option value="Masculino">Masculino</option>
            </select>
        </div>
        <label for="file">Fotografía</label>
        <input type="file" name="file" id="file" accept="image/*">
        <a href="profile.php?user=<?php echo $nombreUsuario; ?>">Cancelar</a>
        <button value="Guardar cambios">Guardar cambios</button>
        <div id="edit-result" class="edit-result"></div>
    </form>

    <script src="../js/custom-select.js"></script>
</body>
</html>