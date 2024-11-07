<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ecommerce_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si un ID est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Supprimer l'utilisateur
    $sql = "DELETE FROM users WHERE id_user = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // Rediriger vers la page principale
    header("Location: main.php");
    exit;
} else {
    echo "ID utilisateur non spécifié.";
}
