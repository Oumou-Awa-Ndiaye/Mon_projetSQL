<?php
require 'vendor/autoload.php';

$faker = Faker\Factory::create('fr_FR'); // Utiliser le local français

// Configuration de la base de données
$host = 'localhost';
$db = 'ecommerce_db'; // Remplacez par votre nom de base de données
$user = 'root'; // Nom d'utilisateur de la base de données
$pass = ''; // Mot de passe

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Insertion des utilisateurs
    for ($i = 0; $i < 10; $i++) {
        $stmt = $pdo->prepare("INSERT INTO Users (Nom, Email, MotDePasse) VALUES (?, ?, ?)");
        $stmt->execute([$faker->name, $faker->unique()->email, password_hash('password123', PASSWORD_BCRYPT)]);
    }

    // 2. Insertion des adresses
    for ($i = 1; $i <= 10; $i++) {
        $stmt = $pdo->prepare("INSERT INTO Address (UserID, Rue, Ville, CodePostal, Pays) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$i, $faker->streetAddress, $faker->city, $faker->postcode, $faker->country]);
    }

    // 3. Insertion des produits
    for ($i = 0; $i < 20; $i++) {
        $stmt = $pdo->prepare("INSERT INTO Product (Nom, Description, Prix, QuantitéStock, CategoryID) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$faker->word, $faker->text, $faker->randomFloat(2, 1, 100), $faker->numberBetween(1, 50), $faker->numberBetween(1, 5)]);
    }

    // 4. Insertion des paniers
    for ($i = 1; $i <= 10; $i++) {
        $stmt = $pdo->prepare("INSERT INTO Cart (UserID, DateCréation) VALUES (?, ?)");
        $stmt->execute([$i, $faker->dateTimeThisYear()->format('Y-m-d H:i:s')]);
    }

    // 5. Insertion des articles dans les paniers
    for ($i = 1; $i <= 10; $i++) {
        for ($j = 0; $j < 3; $j++) { // Chaque panier contient 3 articles
            $stmt = $pdo->prepare("INSERT INTO Cart_Item (CartID, ProductID, Quantité) VALUES (?, ?, ?)");
            $stmt->execute([$i, $faker->numberBetween(1, 20), $faker->numberBetween(1, 5)]);
        }
    }

    // 6. Insertion des commandes avec sélection aléatoire de CartID
    for ($i = 1; $i <= 10; $i++) {
        $cartID = $faker->numberBetween(1, 10); // Sélectionne un CartID existant aléatoire
        $stmt = $pdo->prepare("INSERT INTO Command (UserID, CartID, DateCommande, Statut) VALUES (?, ?, ?, ?)");
        $stmt->execute([$i, $cartID, $faker->dateTimeThisYear()->format('Y-m-d H:i:s'), $faker->randomElement(['En attente', 'Expédiée', 'Livrée'])]);
    }

    // 7. Insertion des factures
    for ($i = 1; $i <= 10; $i++) {
        $stmt = $pdo->prepare("INSERT INTO Invoice (CommandID, MontantTotal, DateFacture) VALUES (?, ?, ?)");
        $stmt->execute([$i, $faker->randomFloat(2, 20, 200), $faker->dateTimeThisYear()->format('Y-m-d H:i:s')]);
    }

    // 8. Insertion des paiements
    for ($i = 1; $i <= 10; $i++) {
        $stmt = $pdo->prepare("INSERT INTO Payment (UserID, MéthodePaiement, IBAN, NuméroCarte) VALUES (?, ?, ?, ?)");
        $stmt->execute([$i, $faker->randomElement(['Carte de crédit', 'IBAN']), $faker->iban('FR'), $faker->creditCardNumber]);
    }

    echo "Données insérées avec succès dans la base de données.";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$pdo = null;
?>
