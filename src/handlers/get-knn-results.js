const suggestionsBtn = document.querySelector('#suggestions-btn');
const recordsBtn = document.querySelector('#all-btn');

suggestionsBtn.addEventListener("click", function () {
    const container = document.getElementById("suggestions");
    const records = document.querySelector('#registros');
    container.innerHTML = "";
    records.style.display = "none";

    // Peticion AJAX para recuperar las recomendaciones
    fetch("../models/run-knn.php")
        .then((response) => response.json())
        .then((data) => {
            data.forEach((rec, idx) => {
                const recommendation = document.createElement("div");
                recommendation.classList.add("recommendation");
                // Imprimimos las recomendaciones en el frontend
                recommendation.innerHTML = `
                    <h3>${rec.title}</h3>
                    <p>${rec.content}</p>
                    <p><strong>Puntaje de similitud:</strong> ${rec.similarity_score.toFixed(4)}</p>
                `;

                container.appendChild(recommendation);
            });
        })
        .catch((error) => {
            console.error("Error al obtener las recomendaciones:", error);
            container.innerHTML = "<p>No se pudieron cargar las recomendaciones</p>";
        });

        container.style.display = "block";
});

recordsBtn.addEventListener("click", function () {
    const container = document.getElementById("suggestions");
    const records = document.querySelector('#registros');
    container.style.display = "none";
    records.style.display = "flex";
});