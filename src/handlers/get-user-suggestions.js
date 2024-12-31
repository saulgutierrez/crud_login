const peopleSuggestionsBtn = document.querySelector('#show-suggestions');

peopleSuggestionsBtn.addEventListener("click", () => {
    fetch("../models/run-knn-user-suggestions.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `user_id=${authUserId}`,
    })
    .then((response) => response.json())
    .then((data) => {
        
    })
    .catch((error) => {
        console.error("Error al obtener las recomendaciones:", error);
    });
});