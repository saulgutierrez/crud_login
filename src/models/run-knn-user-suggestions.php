<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $user_id = $_POST['user_id'];
        // Ejecutar el script de Python
        $command = escapeshellcmd("python3 ../data/runner.py $user_id");
        $output = shell_exec($command);
        $json_file = 'results_users.json';
        if (file_exists($json_file)) {
            // Leer el JSON generado
            $json = file_get_contents('results_users.json');
            echo $json;
        } else {
            echo json_encode(["error" => "No se pudieron generar las recomendaciones"]);
        }
        exit;
    }
?>