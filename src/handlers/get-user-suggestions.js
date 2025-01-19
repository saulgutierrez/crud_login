const peopleSuggestionsBtn = document.querySelector('#show-suggestions');

peopleSuggestionsBtn.addEventListener("click", () => {
    fetch("../models/run-knn-user-suggestions.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `user_id=${authUserId}`,
    })
    .then((response) => response.json())
    .then((data) => {
        const container = document.getElementById("user-suggestions");
        container.innerHTML = "";
        data.forEach((rec) => {
            const div = document.createElement("div");
            div.classList.add("recommendation");
            div.innerHTML = `
                <p>Seguidores comunes: ${rec.seguidores_comunes}</p>
                <p>Likes comunes: ${rec.likes}</p>
            `;
            container.appendChild(div);
        });
    })
    .catch((error) => {
        console.error("Error al obtener las recomendaciones:", error);
    });
});