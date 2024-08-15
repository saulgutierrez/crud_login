<?php
# Se captura la query desde el frontend
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    # Endpoint de busqueda
    $url = "https://www.freetogame.com/api/games";
    # Almacenamoes el contenido de la API en formato JSON
    $response = file_get_contents($url);
    # Si no hay respuesta, termina la ejecución
    if ($response === FALSE) {
        echo json_encode([]);
        exit;
    }
    # Akmacenamos la respuesta en un array asociativo de tipo $var['column']
    $data = json_decode($response, TRUE);
    # Filtramos los resultados con array_filter, recorremos cada elemento, y verificamos si el titulo del juego,
    # coincide con el término de búsqueda.
    $results = array_filter($data, function($item) use ($query) {
        # Retornamos el titulo, si contiene una subcadena que coincida con el término de búsqueda.
        return stripos($item['title'], $query) !== false;
    });
    # Convertimos a JSON, ordenamos de acuerdo a sus pocisiones originales, en la API, y mostramos
    # en el frontend
    echo json_encode(array_values($results));
}
