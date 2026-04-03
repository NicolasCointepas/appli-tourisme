<?php
session_start();
include('db.php');

// Fetch categories
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Categories</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="container">
    <h1>Categories</h1>
    <ul>
        <?php while ($category = $result->fetch_assoc()): ?>
            <li>
                <a href="categorie_template.php?id=<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>

    <!-- Add the Add Category and Delete Category buttons -->
    <button onclick="window.location.href='add_categorie.php'">Ajouter une catégorie</button>
    <button onclick="window.location.href='delete_categorie.php'">Supprimer une catégorie</button>
</div>
</body>
</html>
