$('#generateCodesLink').on('click', function(event) {
    event.preventDefault(); // Evitar la navegación por defecto de la etiqueta <a>
    
    $.ajax({
        url: '../models/save-recovery-codes.php',
        type: 'POST',
        dataType: 'json',
        data: JSON.stringify({ action: 'generateCodes' }),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                $('#codesContainer').empty(); // Limpiar el contenedor de códigos
                $.each(response.codes, function(index, code) {
                    $('#codesContainer').append('<p>' + code + '</p>'); // Mostrar cada código
                });
            } else {
                alert('Error al generar los códigos.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error: ' + error);
        }
    });
});
