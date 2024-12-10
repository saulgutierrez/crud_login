const suggestionsBtn = document.querySelector('#suggestions-btn');
const recordsBtn = document.querySelector('#all-btn');

suggestionsBtn.addEventListener("click", function () {
    const loader = document.getElementById("loader");
    const container = document.getElementById("suggestions");
    const records = document.querySelector('#registros');
    const recordsBtn = document.querySelector('#all-btn');
    container.innerHTML = "";
    records.style.display = "none";
    this.classList.add('suggestions-btn');
    recordsBtn.classList.remove('all-btn');
    loader.style.display = 'flex';

    // Peticion AJAX para recuperar las recomendaciones
    fetch("../models/run-knn.php")
        .then((response) => response.json())
        .then((data) => {
            loader.style.display = 'none';
            data.forEach((rec, idx) => {
                const recommendation = document.createElement("div");
                recommendation.classList.add("recommendation");
                // Imprimimos las recomendaciones en el frontend
                recommendation.innerHTML = `
                    <h2>${rec.author}</h2>
                    <h3>${rec.title}</h3>
                    <p>${rec.content}</p>
                    <p><strong>Puntaje de similitud:</strong> ${rec.similarity_score.toFixed(4)}</p>
                `;

                recommendation.onclick = () => {
                    window.location.href = `view-post.php?id=${rec.id}`;
                }

                container.appendChild(recommendation);
            });
        })
        .catch((error) => {
            console.error("Error al obtener las recomendaciones:", error);
            loader.style.display = 'none';
            container.innerHTML = "<p>No se pudieron cargar las recomendaciones</p>";
        });

        container.style.display = "block";
});

recordsBtn.addEventListener("click", function () {
    const container = document.getElementById("suggestions");
    const records = document.querySelector('#registros');
    const suggestionsBtn = document.querySelector('#suggestions-btn');
    this.classList.add('all-btn');
    suggestionsBtn.classList.remove('suggestions-btn');
    container.style.display = "none";
    records.style.display = "flex";
});