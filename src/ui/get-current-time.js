setInterval(function() {
    const fechaActual = new Date();
    const fechaLocal = fechaActual.toLocaleString(); // Muestra en formato local
    document.getElementById('comment-time').value = fechaLocal;
}, 1000);