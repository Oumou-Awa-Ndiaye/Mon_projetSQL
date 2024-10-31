<?php
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
</html>
