const params = new URLSearchParams(window.location.search);

const btn1 = document.getElementById('btn-1');
const btn2 = document.getElementById('btn-2');
const menuIconos = document.querySelectorAll('.menu-icon');

if (params.has('user')) {
    if (btn1) {
        btn1.classList.remove('hidden');
    }
    
    if (btn2) {
        btn2.classList.remove('hidden');
    }

    if (menuIconos) {
        menuIconos.forEach(elemento => {
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

    if (menuIconos) {
        menuIconos.forEach(elemento => {
            elemento.classList.add('hidden');
        });
    }
}