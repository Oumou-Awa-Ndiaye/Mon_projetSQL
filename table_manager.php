<?php
include 'populate_db.php';

// Récupérer le nom de la table depuis l'URL
$table = $_GET['table'] ?? 'Users';

// Vérifier si un enregistrement doit être supprimé
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $primaryKey = getPrimaryKey($table);

    $sql = "DELETE FROM $table WHERE $primaryKey = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: table_manager.php?table=$table");
        exit;
    } else {
        echo "Erreur lors de la suppression: " . htmlspecialchars($stmt->errorInfo()[2]);
    }
}

// Fonction pour récupérer la clé primaire de la table
function getPrimaryKey($table) {
    $primaryKeys = [
        'Users' => 'id_user',
        'Address' => 'AddressID',
        'Product' => 'ProductID',
        'Cart' => 'CartID',
        'Cart_Item' => 'CartItemID',
        'Command' => 'CommandID',
        'Invoice' => 'InvoiceID',
        'Payment' => 'PaymentID',
        'Photo' => 'PhotoID',
        'Rate' => 'RateID'
    ];
    return $primaryKeys[$table] ?? 'id'; // Valeur par défaut si non défini
}

// Vérifier si un nouvel utilisateur doit être ajouté
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $nom = $_POST['Nom'];
    $email = $_POST['Email'];
    $motDePasse = password_hash($_POST['MotDePasse'], PASSWORD_BCRYPT); // Hasher le mot de passe

    $sql = "INSERT INTO Users (Nom, Email, MotDePasse) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$nom, $email, $motDePasse])) {
        header("Location: table_manager.php?table=Users");
        exit;
    } else {
        echo "Erreur lors de l'ajout: " . htmlspecialchars($stmt->errorInfo()[2]);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de la table <?php echo htmlspecialchars($table); ?></title>
</head>
<body>
    <h1>Gestion de la table <?php echo htmlspecialchars($table); ?></h1>
    <a href="main.php">Retour</a>
    
    <h2>Ajouter un utilisateur</h2>
    <form method="POST">
        <label for="Nom">Nom:</label>
        <input type="text" name="Nom" required>
        <label for="Email">Email:</label>
        <input type="email" name="Email" required>
        <label for="MotDePasse">Mot de passe:</label>
        <input type="password" name="MotDePasse" required>
        <button type="submit" name="add_user">Ajouter</button>
    </form>

    <h2>Liste des enregistrements</h2>
    <table border="1">
        <tr>
            <?php
            // Récupérer les colonnes de la table
            $columnsResult = $conn->query("SHOW COLUMNS FROM $table");
            $columns = [];
            while($col = $columnsResult->fetch(PDO::FETCH_ASSOC)) {
                $columns[] = $col['Field'];
                echo "<th>" . htmlspecialchars($col['Field']) . "</th>";
            }
            ?>
            <th>Action</th>
        </tr>
        
        <?php
        // Récupérer les enregistrements de la table
        $sql = "SELECT * FROM $table";
        $result = $conn->query($sql);

        if ($result->rowCount() > 0) {
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($columns as $col) {
                    echo "<td>" . htmlspecialchars($row[$col]) . "</td>";
                }
                $primaryKey = getPrimaryKey($table);
                if (isset($row[$primaryKey])) { // Vérifier si la clé primaire existe
                    echo "<td>
                            <a href='Edit/edit.php?table=" . htmlspecialchars($table) . "&id=" . htmlspecialchars($row[$primaryKey]) . "'>Modifier</a> |
                            <a href='table_manager.php?table=" . htmlspecialchars($table) . "&delete_id=" . htmlspecialchars($row[$primaryKey]) . "' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?');\">Supprimer</a>
                          </td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='" . (count($columns) + 1) . "'>Aucun enregistrement trouvé</td></tr>";
        }
        ?>
    </table>
</body>
</html>
