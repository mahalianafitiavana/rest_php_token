<?php
require('connexion.php');

function getStudents() {
    $conn = getCon();
    $result = $conn->query("SELECT * FROM students");
        $students = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }
    $conn->close();
    return $students;
}
function create($data) {
    $conn = getCon();
    $name = $conn->real_escape_string($data['name']);
    $age = (int) $data['age'];
    $major = $conn->real_escape_string($data['major']);

    $query = "INSERT INTO students (name, age, major) VALUES ('$name', $age, '$major')";
    if ($conn->query($query) === TRUE) {
        $conn->close();
        return ["message" => "Étudiant ajouté avec succès"];
    } else {
        $conn->close();
        return ["error" => "Erreur lors de l'ajout de l'étudiant"];
    }
}
function verifyToken() {
    // Obtenir tous les en-têtes pour extraire le token
    $headers = getallheaders();

    // Vérifier la présence de l'en-tête Authorization
    if (!isset($headers['Authorization'])) {
        return ["error" => "Token manquant"];
    }
    
    // Extraire le token de l'en-tête Authorization
    list($type, $token) = explode(' ', $headers['Authorization'], 2);
    if (strcasecmp($type, 'Bearer') != 0 || empty($token)) {
        return ["error" => "Format de token invalide"];
    }

    // Vérifier si le token existe dans la base de données
    $conn = getCon();
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $conn->close();
        return ["error" => "Token invalide"];
    }

    $conn->close();
    return null;  // Pas d'erreur, token valide
}

function check_user($data) {
    $conn = getCon();
    $message = array();
    if (isset($data['username']) && isset($data['password'])) {
        $username = $conn->real_escape_string($data['username']);
        $password = hash('sha256', $data['password']);
        // Vérifier les informations de l'utilisateur
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Vérifier le mot de passe (hypothèse : le mot de passe est haché en base)
            if ($password === $user['password']) {
                // Générer un token unique
                $token = bin2hex(random_bytes(16));
                
                // Enregistrer le token dans la base de données
                $updateQuery = "UPDATE users SET token = '$token' WHERE id = " . $user['id'];
                $conn->query($updateQuery);
                
                // Retourner le token à l'utilisateur
                $message = ["token" => $token];
            } else {
                $message = [401 => "Mot de passe incorrect"];
            }
        } else {
            $message = [404 => "Utilisateur non trouvé"];
        }
    } else {
        $message =  [404 => "Nom d'utilisateur ou mot de passe manquant"];
    }
    $conn->close();
    return $message ;
}
