const peopleSuggestionsBtn = document.querySelector('#show-suggestions');

peopleSuggestionsBtn.addEventListener("click", () => {
    const loader = document.getElementById('loader-user-suggestions');
    loader.style.display = "flex";

    // Peticion AJAX para recuperar las recomendaciones
    fetch("../models/run-knn-user-suggestions.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `user_id=${authUserId}`,
    })
    .then((response) => response.json())
    .then((data) => {
        loader.style.display = "none";
        const container = document.getElementById("user-suggestions");
        container.innerHTML = "";
        data.forEach((rec) => {
            const div = document.createElement("div");
            div.classList.add("recommendation");
            div.innerHTML = `
                <div class="recommendation-header">
                    <div class="imgBox">
                        <img src="${rec.fotografia}">
                    </div>
                    <p>${rec.usuario}</p>
                </div>
                <p>${rec.seguidores_comunes} seguidores en comun</p>
                <p>${rec.likes} likes en comun</p>
            `;

            div.onclick = () => {
                window.location.href = `profile.php?id=${rec.user2}`;
            }


            container.appendChild(div);
        });
    })
    .catch((error) => {
        console.error("Error al obtener las recomendaciones:", error);
    });
});