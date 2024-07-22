function loadRecords() {
    $.ajax({
        url: 'load-records.php',
        method: 'GET',
        success: function(data) {
            $('#registros').html(data);
        }
    });
}

$(document).ready(function() {
    // Cargar registros inicialmente
    loadRecords();
    
    // Recargar registros cada 5 segundos
    setInterval(loadRecords, 5000);
});