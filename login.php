<?php
require('inc/functions.php');

// Connexion à la base de données MySQL


// Définir l'en-tête pour le JSON
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $result = check_user($data);
    if (isset($result['token'])) {
        echo json_encode($result);
    }
    if (isset($result[401])) {
        http_response_code(401); // Erreur d'authentification
        echo json_encode($result[401]);
    }
    if (isset($result[404])) {
        http_response_code(404); // Utilisateur non trouvé
        echo json_encode($result[404]);
    }

} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(["error" => "Méthode non autorisée"]);
}
?>