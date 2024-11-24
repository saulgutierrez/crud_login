<?php
    $output = shell_exec('python3 ../data/knn_classifier.py');
    $jsonData = file_get_contents('results.json'); // Lee el archivo JSON generado por Python
    $results = json_decode($jsonData, true); // Decodifica el JSON a un array asociativo
    echo json_encode($results); // Envía los resultados al cliente
?>