<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'student';
$username = 'root';
$password = '';

function getCon()  {
    $conn = new mysqli('localhost', 'root', '', 'student');
    if ($conn->connect_error) {
        die("Erreur de connexion : {$conn->connect_error}");
    }
    return $conn;
}
