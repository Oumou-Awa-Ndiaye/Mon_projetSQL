<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ecommerce_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les tables de la base de données
$tables = [];
$query = $pdo->query("SHOW TABLES");
while ($row = $query->fetch(PDO::FETCH_NUM)) {
    $tables[] = $row[0];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Enregistrements</title>
    <style>
        /* Style de base */
        body {
            font-family: Arial, sans-serif;
            background-color: #fbe4eb;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #d6336c;
            margin-top: 20px;
        }

        form {
            margin: 20px 0;
            text-align: center;
        }

        label {
            font-weight: bold;
            color: #d6336c;
        }

        /* Style du bouton */
        button {
            background-color: #d6336c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #c12c59;
        }

        /* Style de la table */
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #f1c6d7;
        }

        th {
            background-color: #d6336c;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f0f5;
        }

        tr:hover {
            background-color: #f1d1e0;
        }

        /* Style des liens d'actions */
        .action-links a {
            color: #d6336c;
            text-decoration: none;
            margin-right: 10px;
            font-weight: bold;
        }

        .action-links a:hover {
            color: #c12c59;
        }

        /* Centrer le formulaire et la table */
        .container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h1>Sélectionnez une table pour gérer ses enregistrements :</h1>

    <!-- Formulaire pour sélectionner une table -->
    <form method="GET" action="">
        <label for="table">Table :</label>
        <select name="table" id="table">
            <?php foreach ($tables as $table) : ?>
                <option value="<?= htmlspecialchars($table) ?>" <?= isset($_GET['table']) && $_GET['table'] === $table ? 'selected' : '' ?>>
                    <?= htmlspecialchars($table) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Afficher les enregistrements</button>
    </form>

    <?php
    // Afficher les enregistrements de la table sélectionnée
    if (isset($_GET['table']) && in_array($_GET['table'], $tables)) {
        $selectedTable = $_GET['table'];
        echo "<h2>Liste des enregistrements dans la table : " . htmlspecialchars($selectedTable) . "</h2>";

        // Récupérer les enregistrements de la table sélectionnée
        $sql = "SELECT * FROM " . $selectedTable;
        $stmt = $pdo->query($sql);
        $columns = array_keys($stmt->fetch(PDO::FETCH_ASSOC));

        // Afficher les enregistrements sous forme de tableau HTML
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr>";
        foreach ($columns as $column) {
            echo "<th>" . htmlspecialchars($column) . "</th>";
        }
        echo "<th>Action</th>";
        echo "</tr>";

        // Réinitialiser le curseur du statement
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "<td>";
            echo "<a href='edit.php?id=" . $row[$columns[0]] . "&table=" . urlencode($selectedTable) . "'>Éditer</a> | ";
            echo "<a href='delete.php?id=" . $row[$columns[0]] . "&table=" . urlencode($selectedTable) . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet enregistrement ?\");'>Supprimer</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    ?>
</body>
</html>
