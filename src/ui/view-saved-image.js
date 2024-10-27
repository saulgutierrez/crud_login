// Obtener el modal
var modal = document.getElementById("myModal");
// Obtener el enlace que abre el modal
var link = document.getElementById("openModalLink");
// Obtener el elemento <span> que cierra el modal
var span = document.getElementsByClassName("close")[0];
// Cuando el usuario hace clic en el enlace, se abre el modal y se previene la recarga de la página
link.onclick = function(event) {
//   event.preventDefault(); // Prevenir el comportamiento por defecto del enlace
  modal.style.display = "block";
}
// Cuando el usuario hace clic en <span> (x), se cierra el modal
span.onclick = function() {
  modal.style.display = "none";
}
// Cuando el usuario hace clic en cualquier parte fuera del modal, se cierra el modal
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}