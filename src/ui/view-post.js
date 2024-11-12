const username = document.getElementById("username");
const postCard = document.querySelector('.post-card');

// Función para verificar y agregar la clase overflow-visible
// (para mostrar el card de informacion basica del perfil al hacer hover y genere un overflow sobre el card contenedor,
// solo en caso de que este sea mas pequeño que el card de informacion basica, en caso contrario, el comportamiento
// del card contenedor, seguira como esta (scroll-y))
function checkOverflow() {
    const infoPopup = document.querySelector('.info-popup');
    if (infoPopup) {
        const infoPopupHeight = infoPopup.offsetHeight;
        const postCardHeight = (postCard.offsetHeight / 3);

        if (infoPopupHeight > postCardHeight) {
            postCard.classList.add('overflow-visible');
        }

        // Aseguramos que no se quite la clase mientras estamos dentro de infoPopup o sus hijos
        infoPopup.addEventListener('mouseleave', () => {
            postCard.classList.remove('overflow-visible');
        });

        // También añadimos un mouseenter para asegurar que la clase se mantenga al volver a entrar
        infoPopup.addEventListener('mouseenter', () => {
            if (infoPopupHeight > postCardHeight) {
                postCard.classList.add('overflow-visible');
            }
        });
    }
}

// Observa cambios en el DOM cuando se hace hover sobre username
username.addEventListener('mouseenter', () => {
    // Observador para detectar cuándo info-popup se agrega al DOM
    const observer = new MutationObserver((mutationsList, observer) => {
        for (const mutation of mutationsList) {
            if (mutation.type === 'childList') {
                checkOverflow();
                observer.disconnect();
            }
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });
});

// Quitamos la clase overflow-visible cuando el mouse realmente sale de username
username.addEventListener('mouseleave', () => {
    postCard.classList.remove('overflow-visible');
});