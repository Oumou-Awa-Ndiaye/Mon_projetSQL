<?php
$host = 'localhost';
$dbname = 'ecommerce_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connexion réussie!"; // À désactiver pour éviter d'afficher ce message à l'utilisateur
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
