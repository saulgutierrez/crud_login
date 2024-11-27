document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("suggestions");

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
});