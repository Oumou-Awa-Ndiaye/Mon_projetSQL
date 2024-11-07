<!-- <?php
include 'populate_db.php'; // Connexion à la base de données

// Récupérer la liste des tables dans la base de données
$tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de la Base de Données E-commerce</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        ul { list-style-type: none; padding: 0; }
        li { margin: 10px 0; }
        a { text-decoration: none; color: #007BFF; font-weight: bold; }
        a:hover { text-decoration: underline; }
        .container { max-width: 600px; margin: auto; }
        .table-link { font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion de la Base de Données E-commerce</h1>
        <p>Sélectionnez une table pour gérer ses enregistrements :</p>
        <ul>
            <?php foreach ($tables as $table): ?>
                <li><a class="table-link" href="table_manager.php?table=<?php echo htmlspecialchars($table); ?>">Gérer la table <?php echo ucfirst(htmlspecialchars($table)); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html> -->
<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ecommerce_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Requête pour obtenir les utilisateurs
$sql = "SELECT id_user, Nom, Email, MotDePasse FROM users";
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des enregistrements</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h1>Liste des enregistrements</h1>
    <table>
        <tr>
            <th>id_user</th>
            <th>Nom</th>
            <th>Email</th>
            <th>MotDePasse</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <td><?= htmlspecialchars($row['id_user']) ?></td>
                <td><?= htmlspecialchars($row['Nom']) ?></td>
                <td><?= htmlspecialchars($row['Email']) ?></td>
                <td><?= htmlspecialchars($row['MotDePasse']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id_user'] ?>">Éditer</a> |
                    <a href="delete.php?id=<?= $row['id_user'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
