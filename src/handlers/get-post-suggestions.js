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
    fetch("../models/run-knn-post-suggestions.php")
        .then((response) => response.json())
        .then((data) => {
            loader.style.display = 'none';
            data.forEach((rec, idx) => {
                const recommendation = document.createElement("div");
                recommendation.classList.add("recommendation");
            
                // Crear el enlace
                const authorLink = document.createElement("a");
                authorLink.href = `profile.php?id=${rec.id_author}`;
                authorLink.textContent = rec.author;
                authorLink.onclick = (event) => {
                    event.stopPropagation();
                };
            
                // Crear el contenedor de la imagen
                const imgBox = document.createElement("div");
                imgBox.classList.add("imgBox");
            
                const img = document.createElement("img");
                img.src = rec.author_photo;
                imgBox.appendChild(img);
            
                // Crear el contenedor flex
                const authorContainer = document.createElement("div");
                authorContainer.classList.add("authorContainer");
                authorContainer.appendChild(imgBox);
                authorContainer.appendChild(authorLink);

                // Porcentaje de similitud
                let similarityScore = rec.similarity_score.toFixed(4);
                let percentaje = (similarityScore * 100).toFixed(4);
            
                // Configurar el contenido principal
                recommendation.innerHTML = `
                    <div class='suggestion-container'>
                        <div>
                            <h3>${rec.title}</h3>
                            <p>${rec.content}</p>
                        </div>
                        <div>
                            <p class='similarity-score'> ${percentaje} %</p>
                        </div>
                    </div>
                `;
            
                // Insertar el contenedor flex al inicio
                recommendation.prepend(authorContainer);
            
                recommendation.onclick = () => {
                    window.location.href = `view-post.php?id=${rec.id}`;
                };
            
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