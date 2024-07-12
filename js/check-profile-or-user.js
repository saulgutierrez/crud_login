const params = new URLSearchParams(window.location.search);

const btn1 = document.getElementById('btn-1');
const btn2 = document.getElementById('btn-2');
// const btn3 = document.getElementById('btn-3');
// const btn4 = document.getElementById('btn-4');
const menuIconos = document.querySelectorAll('.menu-icon');
const menuIconosComments = document.querySelectorAll('.menu-icon-comments');

if (params.has('user')) {
    if (btn1) {
        btn1.classList.remove('hidden');
    }
    
    if (btn2) {
        btn2.classList.remove('hidden');
    }

    if (btn3) {
        btn3.classList.add('hidden');
    }

    if (menuIconos) {
        menuIconos.forEach(elemento => {
            elemento.classList.remove('hidden');
        });
    }

    if (menuIconosComments) {
        menuIconosComments.forEach(elemento => {
            elemento.classList.remove('hidden');
        });
    }
} else if (params.has('id')) {
    if (btn1) {
        btn1.classList.add('hidden');
    }
    if (btn2) {
        btn2.classList.add('hidden');
    }

    // if (btn3) {
    //     btn3.classList.remove('hidden');
    // }

    if (menuIconos) {
        menuIconos.forEach(elemento => {
            elemento.classList.add('hidden');
        });
    }

    if (menuIconosComments) {
        menuIconosComments.forEach(elemento => {
            elemento.classList.add('hidden');
        });
    }
}

// btn3.addEventListener('click', () => {
//     event.preventDefault();
//     btn3.classList.add('hidden');
//     btn4.classList.remove('hidden');
// });

// btn4.addEventListener('mouseover', () => {
//     btn4.innerHTML = "Dejar de seguir";
// });

// btn4.addEventListener('mouseleave', () => {
//     btn4.innerHTML = "Siguiendo";
// });

// btn4.addEventListener('click', () => {
//     btn4.classList.add('hidden');
//     btn3.classList.remove('hidden');
// });