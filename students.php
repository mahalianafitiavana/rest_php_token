<?php
require('inc/functions.php');

// Définir l'en-tête pour indiquer que la réponse sera au format JSON
header("Content-Type: application/json; charset=UTF-8");

// Gérer la requête en fonction de la méthode HTTP
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Récupérer tous les étudiants
        $students = getStudents();
        echo json_encode($students, JSON_PRETTY_PRINT);
        break;

    case 'POST':
        // Vérifier le token avant d'ajouter un étudiant
        $error = verifyToken();

        if ($error){
            http_response_code(401); // Non autorisé
            echo json_encode($error);
            exit();
        }

        // Ajouter un nouvel étudiant
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['name']) && isset($data['age']) && isset($data['major'])) {
            $result = create(data: $data);
            if ($result['message']) {
                http_response_code(201); // Code 201 : Créé avec succès
                echo json_encode($result);
            } else {
                http_response_code(500); // Code 500 : Erreur serveur
                echo json_encode($result);
            }
        } else {
            http_response_code(400); // Code 400 : Requête incorrecte
            echo json_encode(["error" => "Données manquantes pour l'ajout de l'étudiant"]);
        }
        break;

    default:
        http_response_code(405); // Code 405 : Méthode non autorisée
        echo json_encode(["error" => "Méthode non autorisée"]);
        break;
}


?>