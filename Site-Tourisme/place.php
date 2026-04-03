<?php
session_start();
include('db.php');

$sql = "SELECT * FROM places";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des lieux</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="container">
    <h1>Liste des lieux</h1>
    <a href="add_place.php">Ajouter un lieu</a>
    <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Adresse</th>
            <th>Ville</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while($place = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($place['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($place['address'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($place['city'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td>
                    <a href="place_template.php?id=<?php echo $place['id']; ?>">Voir</a>
                    <a href="modify_place.php?id=<?php echo $place['id']; ?>">Modifier</a>
                    <a href="delete_place.php?id=<?php echo $place['id']; ?>" onclick="return confirm('Tu es sûr de vouloir supprimer cet endroit ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

