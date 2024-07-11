setInterval(function() {
    var fechaActual = new Date().toISOString().slice(0, 19).replace('T', ' ');
    document.getElementById('comment-time').value = fechaActual;
}, 1000);