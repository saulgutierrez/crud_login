const peopleSuggestionsBtn = document.querySelector('#show-suggestions');

peopleSuggestionsBtn.addEventListener("click", () => {
    fetch("../models/run-knn-user-suggestions.php")
        .then((response) => response.json())
        .then((data) => {

        })
        .catch((error) => {
            console.error("Error al obtener las recomendaciones:", error);
        });
});