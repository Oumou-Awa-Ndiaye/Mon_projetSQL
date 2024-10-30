<?php
require 'vendor/autoload.php'; 


try {
    $pdo = new PDO('mysql:host=localhost;dbname=ecommerce_db', 'root', ''); // Établir la connexion
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Pour gérer les erreurs
    echo "Connexion réussie à la base de données !<br>";
} catch (PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage() . "<br>";
    exit; // Arrêter l'exécution si la connexion échoue
}

// Utilisation de Faker pour générer des données fictives
$faker = Faker\Factory::create();

// Générer et insérer des utilisateurs fictifs
for ($i = 0; $i < 10; $i++) {
    $nom = $faker->name;
    $email = $faker->unique()->email;
    $motDePasse = password_hash('motdepasse123', PASSWORD_BCRYPT); // Hachage du mot de passe

    // Préparer et exécuter la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO User (Nom, Email, MotDePasse) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $email, $motDePasse]);
}

echo "Utilisateurs ajoutés avec succès !";
?>
