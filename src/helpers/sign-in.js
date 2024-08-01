document.querySelector('.submit-container').addEventListener('click', function () {
    document.getElementById('show-message').click();
});

document.querySelector('.submit-container').addEventListener('mouseover', function () {
    // Iterar sobre los hijos y aplicar el estilo a cada uno
    this.style.backgroundColor = "hsl(211, 100%, 43%)";
    Array.from(this.children).forEach(child => {
        child.style.backgroundColor = "hsl(211, 100%, 43%)";
    });
});

document.querySelector('.submit-container').addEventListener('mouseleave', function () {
    this.style.backgroundColor = "hsl(211, 100%, 50%)"; // Color del contenedor padre
    Array.from(this.children).forEach(child => {
        child.style.backgroundColor = "hsl(211, 100%, 50%)"; // Color de los hijos
    });
});