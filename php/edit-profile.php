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
        <a href="profile.php?user=<?php echo $nombreUsuario; ?>">Cancelar</a>
        <button value="Guardar cambios">Guardar cambios</button>
        <div id="edit-result" class="edit-result"></div>
    </form>

    <script>
        var x, i, j, l, ll, selElmnt, a, b, c;
        /* Look for any elements with the class "custom-select": */
        x = document.getElementsByClassName("custom-select");
        l = x.length;
        for (i = 0; i < l; i++) {
        selElmnt = x[i].getElementsByTagName("select")[0];
        ll = selElmnt.length;
        /* For each element, create a new DIV that will act as the selected item: */
        a = document.createElement("DIV");
        a.setAttribute("class", "select-selected");
        a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
        x[i].appendChild(a);
        /* For each element, create a new DIV that will contain the option list: */
        b = document.createElement("DIV");
        b.setAttribute("class", "select-items select-hide");
        for (j = 1; j < ll; j++) {
            /* For each option in the original select element,
            create a new DIV that will act as an option item: */
            c = document.createElement("DIV");
            c.innerHTML = selElmnt.options[j].innerHTML;
            c.addEventListener("click", function(e) {
                /* When an item is clicked, update the original select box,
                and the selected item: */
                var y, i, k, s, h, sl, yl;
                s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                sl = s.length;
                h = this.parentNode.previousSibling;
                for (i = 0; i < sl; i++) {
                if (s.options[i].innerHTML == this.innerHTML) {
                    s.selectedIndex = i;
                    h.innerHTML = this.innerHTML;
                    y = this.parentNode.getElementsByClassName("same-as-selected");
                    yl = y.length;
                    for (k = 0; k < yl; k++) {
                    y[k].removeAttribute("class");
                    }
                    this.setAttribute("class", "same-as-selected");
                    break;
                }
                }
                h.click();
            });
            b.appendChild(c);
        }
        x[i].appendChild(b);
        a.addEventListener("click", function(e) {
            /* When the select box is clicked, close any other select boxes,
            and open/close the current select box: */
            e.stopPropagation();
            closeAllSelect(this);
            this.nextSibling.classList.toggle("select-hide");
            this.classList.toggle("select-arrow-active");
        });
        }

        function closeAllSelect(elmnt) {
        /* A function that will close all select boxes in the document,
        except the current select box: */
        var x, y, i, xl, yl, arrNo = [];
        x = document.getElementsByClassName("select-items");
        y = document.getElementsByClassName("select-selected");
        xl = x.length;
        yl = y.length;
        for (i = 0; i < yl; i++) {
            if (elmnt == y[i]) {
            arrNo.push(i)
            } else {
            y[i].classList.remove("select-arrow-active");
            }
        }
        for (i = 0; i < xl; i++) {
            if (arrNo.indexOf(i)) {
            x[i].classList.add("select-hide");
            }
        }
        }

        /* If the user clicks anywhere outside the select box,
        then close all select boxes: */
        document.addEventListener("click", closeAllSelect);
    </script>
</body>
</html>