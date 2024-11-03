<?php
include 'populate_db.php';

// Récupérer le nom de la table et l'ID de l'utilisateur à modifier
$table = $_GET['table'] ?? 'Users';
$id = $_GET['id'] ?? null;

// Vérifier si l'utilisateur doit être modifié
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    $nom = $_POST['Nom'];
    $email = $_POST['Email'];
    $motDePasse = password_hash($_POST['MotDePasse'], PASSWORD_BCRYPT); // Hasher le mot de passe

    $sql = "UPDATE Users SET Nom = ?, Email = ?, MotDePasse = ? WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$nom, $email, $motDePasse, $id])) {
        header("Location: ../table_manager.php?table=Users");
        exit;
    } else {
        echo "Erreur lors de la modification: " . htmlspecialchars($stmt->errorInfo()[2]);
    }
}

// Récupérer les informations de l'utilisateur
$sql = "SELECT * FROM Users WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'utilisateur</title>
</head>
<body>
    <h1>Modifier l'utilisateur</h1>
    <a href="../main.php">Retour</a>

    <form method="POST">
        <label for="Nom">Nom:</label>
        <input type="text" name="Nom" value="<?php echo htmlspecialchars($user['Nom']); ?>" required>
        <label for="Email">Email:</label>
        <input type="email" name="Email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
        <label for="MotDePasse">Mot de passe:</label>
        <input type="password" name="MotDePasse" placeholder="Laissez vide pour ne pas changer">
        <button type="submit" name="edit_user">Modifier</button>
    </form>
</body>
</html>
