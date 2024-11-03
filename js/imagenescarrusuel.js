document.addEventListener("DOMContentLoaded", async function () {
    // Obtiene la BASE_URL desde tu API
    let baseUrl;
    try {
        const response = await fetch('/api/index.php?get=BASE_URL');
        if (response.ok) {
            const data = await response.json();
            baseUrl = data.baseUrl;
            console.log("Base URL:", baseUrl); // Esto mostrará la URL base en la consola
        } else {
            console.error('Error al obtener la BASE_URL:', response.status, response.statusText);
            return; // Detiene la ejecución si no se puede obtener la BASE_URL
        }
    } catch (error) {
        console.error('Error en la solicitud a la API:', error);
        return; // Detiene la ejecución si ocurre un error
    }

    // Crear URLs de imágenes utilizando baseUrl
    const imageUrls = [];
    for (let i = 1; i <= 15; i++) {
        imageUrls.push(`${baseUrl}/wp-content/uploads/2024/11/imagen${i}.png`);
    }

    const gridContainer = document.getElementById('gridContainer');

    // Agregar imágenes al contenedor
    imageUrls.forEach((url, index) => {
        const div = document.createElement('div');
        div.className = 'grid-item';
        if (index === 0) {
            div.classList.add('tall');
        } else if (index === 2) {
            div.classList.add('wide');
        } else if (index === 8) {
            div.classList.add('tall', 'wide');
        }
        div.style.backgroundImage = `url('${url}')`;
        gridContainer.appendChild(div);
    });
});
