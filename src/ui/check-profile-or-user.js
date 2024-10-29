const params = new URLSearchParams(window.location.search);

const btn1 = document.getElementById('btn-1');
const btn2 = document.getElementById('btn-2');
const btn3 = document.getElementById('btn-3');
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
        const children = btn1.querySelectorAll('*');
        children.forEach(child => {
            child.classList.add('hidden');
        });
    }
    if (btn2) {
        btn2.classList.add('hidden');
    }

    if (btn3) {
        btn3.classList.remove('hidden');
     }

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