const submitContainer = document.getElementById('submitContainer');
const submitButton = document.getElementById('show-message');
const form = document.getElementById('myForm');

submitContainer.addEventListener('click', function (event) {
    event.preventDefault();
    submitButton.click(); // Simulamos un click en el boton de envio
});

submitButton.addEventListener('click', function(event) {
    event.stopPropagation(); // Detener la propagación del evento al contenedor
    form.submit(); // Enviar el formulario
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