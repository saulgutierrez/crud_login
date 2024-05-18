$(document).ready(function () {
    $('#editProfileForm').on('submit', function(event) {
        event.preventDefault();
        var datosEnviados = {
            'id'                :   $('#id').val(),
            'user'              :   $('#user').val(),   
            'password'          :   $('#password').val(),
            'nombre'            :   $('#nombre').val(),
            'apellido'          :   $('#apellido').val(),
            'correo'            :   $('#correo').val(),
            'telefono'          :   $('#telefono').val(),
            'fechanacimiento'   :   $('#fechanacimiento').val(),
            'genero'            :   $('#genero').val()
            // 'foto'              :   $('#foto').val()
        };

        if ($('#user').val() == "" || $('#password').val() == "") {
            $('#edit-result').html('El usuario y contraseña son campos obligatorios');
            $('#edit-result').show();
            setTimeout("$('#edit-result').html('')", 5000);
        } else {
            $.ajax({
                url         :   'edit-data.php',
                type        :   'POST',
                data        :   datosEnviados,
                dataType    :   'text',
                success:    function(res) {
                    if (res == 0) {
                        location.href = "dashboard.php";
                    } else if (res == 1) {
                        alert('Error al actualizar');
                    } else if (res == 2) {
                        alert('El usuario ingresado ya existe');
                    } else {
                        alert(res);
                    }
                },
                error:  function (e) {
                    alert('Error');
                }
            });
        }
    });
});